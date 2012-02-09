<?php

/**
 * \brief Driver for PDO with MySQL
 */
class DbPdo_mysql extends DbMysql_query_builder {

    /**
     * @var PDO
     */
    public $connection = null;
    
    /**
     * @var PDOStatement 
     */
    public $statement = null;

    public $persitent = true;               ///< Set true for persitent connection
    public $fetchStyle = PDO::FETCH_ASSOC;  ///< How "select" queries will return rows from database. \see http://www.php.net/manual/en/pdostatement.fetch.php
    protected $affectedRows = null;

    public function __construct($dbConfig) {
        $this->connect($dbConfig);
        // set escape callback
//        $this->escapeCallback = array($this->connection, 'quote');  // Found some problems regarding implementing in DbMysql_query_builder class.
        $this->escapeCallback = 'addslashes';  // Use this method instead.
        // set row-fetch callback parameter
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
            throw new Exception('[PHPizza] Error occured in SELECTing from database');

        // Set fetch callbacks
        $this->fetchCallback = array($this->statement, 'fetch');
        $this->fetchArg1 = $this->fetchStyle;

        if ($this->returnPointer)
            return $this->statement;
        else
            return $this->statement->fetch($this->fetchStyle);
    }

    public function deleteArray() {
        // Must provide an identifier. 
        if (!$this->identifier)
            return false;
        $this->deleteArrayQuery();
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

    /**
     * @name Prepared Statement Functionality
     */
    //@{

    /**
     * \brief INSERT operation using Prepared Statement.
     * The only difference with insertArray() is that, this time you need to pass an <b>one-dimentional array</b>
     * instead of associative-array to $this->data. You should only pass a simple array of desired <i>column</i>'s names.
     * \see Learn about prepared statements: http://www.php.net/manual/en/pdo.prepared-statements.php
     */
    public function prepareInsert() {
        $this->prepareInsertQuery();
        $this->statement = $this->connection->prepare($this->query);
//        $this->debug(true);
        return $this->statement;
    }
    
    /**
     * \brief SELECT operation using Prepared Statement
     * The only difference with selectArray() is: in this function you need to pass an <b>one-dimentional array</b> 
     * instead of associative-array to $this->identifier. You should only pass a simple array of desired <i>column</i>'s names.
     * \see Learn about prepared statements: http://www.php.net/manual/en/pdo.prepared-statements.php
     */
    
    public function prepareSelect(){
        $this->prepareSelectQuery();
        $this->statement = $this->connection->prepare($this->query);
//        $this->debug(true);
        return $this->statement;
    }
    
    /**
     * \brief Executes the prepared statement: $this->statement
     * @param array $inputs - One-dimentional array of values with as many elements as there are bound parameters in the SQL statement being executed.
     * @return bool - true in success, false otherwise.
     */
    
    public function execute($inputs=null){
        return $this->statement->execute($inputs);
    }

    //@}

    /**
     * @name Overriding parent's methods
     */
    
    //@{

    /**
     * Clears class variables/sets to default values. 
     */
    public function clear() {
        $this->select = null;
        $this->data = null;
        $this->identifier = null;
        $this->tableJoinIdentifier = null;
        $this->rest = "";
        $this->returnPointer = true;
        $this->joiner = "AND";
        $this->query = "";
        $this->returnInsertID = true;
        $this->statement = null;
        $this->fetchArg1 = PDO::FETCH_ASSOC;
    }

    //@}
    
    public function escape($textToEscape) {
        return $this->connection->quote($textToEscape);
    }
}

?>
