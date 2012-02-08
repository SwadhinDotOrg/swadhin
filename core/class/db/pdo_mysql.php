<?php

/**
 * \brief Driver for PDO with MySQL
 */
class DbPdo_mysql extends DbMysql_query_builder {

    /**
     *
     * @var $this->connection PDO 
     */
//    public $connection = null;

    public $persitent = true;               ///< Set true for persitent connection
    public $fetchStyle = PDO::FETCH_ASSOC;  ///< How "select" queries will return rows from database. \see http://www.php.net/manual/en/pdostatement.fetch.php
    protected $affectedRows = null;

    public function __construct($dbConfig) {
        $this->connect($dbConfig);
        // set escape callback
//        $this->escapeCallback = array($this->connection, 'quote');  // Found some problems regarding implementing in DbMysql_query_builder class.
        $this->escapeCallback = 'addslashes';  // Use this method instead.
        // set row-fetch callback
        $this->fetchCallback = array($this->statement, 'fetch');
        $this->fetchArg1 = $this->fetchStyle;
    }

    public function connect($dbConfig) {
        $this->connection = new PDO('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['database'], $dbConfig['username'], $dbConfig['password'], array(
                    PDO::ATTR_PERSISTENT => $this->persitent,
                    pdo::ATTR_ERRMODE => pdo::ERRMODE_EXCEPTION
                ));
    }

    public function setError() {
        $error = $this->connection->errorInfo();
        $this->errorNo = $error[1] . '(SQLSTATE ' . $error[0] . ')';
        $this->errorMsg = $error[2];
    }

    public function insertArray() {
        $this->insertArrayQuery();
//        $this->debug(true);
        $this->affectedRows = $this->connection->exec($this->query);

        if ($this->affectedRows === false)
            return false;

        return ($this->returnInsertID) ? ($this->connection->lastInsertId()) : (true);
    }

    public function updateArray() {
        $this->updateArrayQuery();
        $this->affectedRows = $this->connection->exec($this->query);
        return $this->affectedRows;
    }

    public function selectArray() {
        $this->selectArrayQuery();
        $this->statement = $this->connection->query($this->query);
        if ($this->statement === false)
            throw new Exception('[PHPizza] Error occured in selecting from database');

        // Set fetch callbacks
        $this->fetchCallback = array($this->statement, 'fetch');
        $this->fetchArg1 = $this->fetchStyle;

        if ($this->returnPointer)
            return $this->statement;
        else
            return $this->statement->fetch($this->fetchStyle);
    }

    public function delete() {
        // Must provide an identifier. 
        if (!$this->identifier)
            return false;
        $this->deleteQuery();
        return $this->affectedRows = $this->connection->exec($this->query);
    }
    
    public function fetch() {
        return $this->statement->fetch($this->fetchStyle);
    }


    public function numAffectedRows() {
        return $this->affectedRows;
    }

    public function numReturnedRows() {
        return $this->affectedRows;
    }

}

?>
