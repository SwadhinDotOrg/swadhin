<?php

/**
 * \define Abstract Database class. All Database driver classes must implement it.
 * 
 * @author Shafiul Azam
 */
abstract class DbGeneric {

    public $table = "";  ///< Name of the table (schema) on which db operation will take place
    public $data = null;   ///<  Associative array of Data to insert/update
    public $encryptedData = null;  ///< Associative array of Data. Key is column-name & Value is data to insert encrypting using $this->encryptionFunction in that column.
    public $identifier = null; ///< Associative array of identifier to be used after WHERE clause (i.e. $identifier["username"] => "giga" is for WHILE `username` = 'giga')
    public $encryptedIdentifier = null;     ///< ///< Associative array of identifier to be used after WHERE clause. Values will be encrypted using $this->encryptionFunction
    public $tableJoinIdentifier = null;
    public $select = null;  ///<    Array, entries denote which columns will be selected in selectArray()
    public $encryptionFunction = "SHA"; ///< Name of the encryption function to apply on $valueToEncrypt in insertArray()
    public $errorNo;    ///<    Error number, if error occurs
    public $errorMsg;   ///<    Descriptive error message, if occurs
    public $query = ""; ///<    Generated query (including $this->rest)
    public $rest = "";   ///<  Additional query to append after $query
    public $joiner = "AND"; ///<    joiner (AND/OR) for WHERE clause. Possible values: "AND" "OR"
    public $returnPointer = true;  ///<     Return pointer/single data from "select" operations. If set to false, only a single row of database will be returned in the form of an associative array.  
    public $returnInsertID = true;  ///< Boolean, whether returns "Insert ID" for select command. 
    public $connection = null;    ///<    Connection identifier after successful database connection
    
    public $statement = null; ///< For using in drivers which support statements. \see http://www.php.net/manual/en/class.pdostatement.php
    public $result  = null;     ///< For storing result-pointer after executing various instructions.
    public $fetchCallback = null;       ///< Callback for fetching each row, iteratively
    public $fetchArg1 = null;           ///< 1st Argument/Parameter to $fetchCallback
    // Functions to implement

    /**
     * Establishes database connection. \n
     * First function before all other functions can be called, to establish the database connection.
     * 
     * @param mixed $dbConfig - Associative array containing DB credential
     */
    abstract public function connect($dbConfig);

    /**
     * Inserts $this->data to schema $this->table. You can encrypt some column's value by using the optional parameters
     * @param "string" $columnToEncrypt name of the column whose value will be encrypted
     * @param "string" $valueToEncrypt this value will be encrypted using $this->encryptionFunction function and stored in $columnToEncrypt column
     * @return mixed
     *  - a positive integer, the auto-incremented column's value if possible. Otherwise, boolean true
     *  - bool false for failure
     */
    abstract public function insertArray();

    /**
     * Updates $this->data to database schema $this->table, WHERE $this->identifier are joined by $this->joiner followed by $this->rest
     * @return bool true for success, false for failure
     */
    abstract public function updateArray();

    /**
     * SELECTS $this->select from schema $this->table WHERE $this->identifier are joined by $this->joiner followed by $this->rest
     * @return mixed
     *  - if $this->returnPointer is false (returns single row), just returns a Associative pair of the returned row, key being the column name & value being the value for the column.
     * 
     *  If $this->returnPointer is true (returns multiple row), then returns a pointer variable for retuned row(s). You call database specific function to iterate through all rows. 
     *  - boolfalse for failure
     */
    abstract public function selectArray();

    /**
     * DELETE row(s) from schema $this->table WHERE $this->identifier are joined by $this->joiner followed by $this->rest
     * @return bool true for success, false for failure
     */
    abstract public function deleteArray();
    
    /**
     * @name Fetching Rows after selectArray()
     */
    
    //@{
    
    /**
     * Fetch the next row after executing selectArray()
     * @return array - Associaive array - <i>key</i> is column name, <i>value</i> is data for that column.
     */
    abstract public function fetch();
    
    //@}

    // utility

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
    }

    // Status Related

    
    /**
     * Number of affected rows after insertArray(), updateArray() or delete()
     * @return int
     */
    abstract public function numAffectedRows();
    
    // Knowing about Error

    /**
     * Internal function, to set errors (if any occured)
     * @return int
     */
    abstract public function setError();

    // Implemented utility functions

    /**
     * Function for debuggin. First finds if any occured by setError() then prints the error 
     * if DB_DEBUG_MODE_ON constant is set true.
     */
    public function debug($exit=false) {
        if (DEBUG_MODE) {
            $this->setError();
            echo "<PRE>";
            echo "Query: " . $this->query;
            echo "<br />Error: " . $this->errorNo . " " . $this->errorMsg;
            echo "</PRE> <br />";
            if ($exit)
                exit();
        }
    }

}

?>