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
    }

    public function connect($dbConfig) {
        $this->connection = new PDO('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['database'], $dbConfig['username'], $dbConfig['password'], array(
                    PDO::ATTR_PERSISTENT => $this->persitent,
                    pdo::ATTR_ERRMODE => pdo::ERRMODE_EXCEPTION
                ));
    }

    public function setError() {
        ;
    }

    public function insertArray() {
        $this->insertArrayQuery();
        $result = $this->connection->exec($this->query);
        if($result === false)
            return false;
        else
            $this->affectedRows = $result;
        
        return ($this->returnInsertID)?($this->connection->lastInsertId()):(true);
    }

    public function updateArray() {
        ;
    }

    public function selectArray() {
        ;
    }

    public function delete() {
        ;
    }

    public function clear() {
        ;
    }

    public function numAffectedRows() {
        ;
    }

    public function numReturnedRows() {
        ;
    }

}

?>
