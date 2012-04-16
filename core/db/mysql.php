<?php

/**
 * \brief database functionality class for "mysql"
 * 
 * @author Shafiul Azam
 */

class DbMysql extends DbMysql_query_builder{
        
    /**
     * Constructor, just calls connect()
     */
    
    public function __construct($dbConfig) {
        // connect!
        $this->connect($dbConfig);
        // set escape callback
        $this->escapeCallback = 'mysql_real_escape_string';
        // set fetch callback
        $this->fetchCallback = 'mysql_fetch_array';
    }
    
    public function setError() {
        $this->errorNo = mysql_errno($this->connection);
        $this->errorMsg = mysql_error($this->connection);
    }
    
    public function connect($dbConfig) {
        $this->connection = mysql_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password']);
        if(!$this->connection){
            $this->errorNo = 0;
            $this->errorMsg = "Connection Failed!";
//            $this->debug();
            throw new Exception('Database Error: ' . $this->errorMsg);
        }
        if($dbConfig['database'] && !mysql_select_db($dbConfig['database'], $this->connection)){
            $this->setError();
//            $this->debug();
            throw new Exception('Database Error: ' . $this->errorMsg);
        }
        return $this->connection;
    }
    
    /**
     * Inserts $this->data to schema $this->table. You can encrypt some column's value by using the optional parameters
     * @param "string" $columnToEncrypt name of the column whose value will be encrypted
     * @param "string" $valueToEncrypt this value will be encrypted using $this->encryptionFunction function and stored in $columnToEncrypt column
     * @return mixed
     *  - a non-negative integer, the auto-incremented column's value. 
     *  - bool false for failure
     */
    
    public function insertArray() {
        // Prepare Query
        $this->insertArrayQuery();
        $this->result = mysql_query($this->query);
//        $this->debug();
        if ($this->result) {
            return ($this->returnInsertID)?(mysql_insert_id()):(true);
        } else {
            return false;
        }
    }
    
    
    public function updateArray() {
        // Generate Query
        $this->updateArrayQuery();
        
        $this->result = mysql_query($this->query);
//        $this->debug();
        return $this->result;
    }
    
    /**
     * SELECTS $this->select from schema $this->table WHERE $this->identifier are joined by $this->joiner followed by $this->rest
     * @return mixed
     *  - if $this->returnPointer is false (returns single row), just returns a key-value pair of the returned row, key being the column name & value being the value for the column.
     * 
     *  If $this->returnPointer is true (returns multiple row), then returns a pointer variable for retuned row(s). You call mysql_fetch_array() with the pointer as argument to iterate through all rows. 
     *  - boolfalse for failure
     */
    
    public function selectArray() {
        // Generate Query
        $this->selectArrayQuery();
        
        $this->result = mysql_query($this->query);
        // Update fetch-callback's argument
        $this->fetchArg1 = $this->result;
//        $this->debug();
        if ($this->returnPointer) {
            // Return link to resources
            return $this->result;
        } else {
            // Return only the first result
            return mysql_fetch_array($this->result);
        }
    }
    
    public function deleteArray() {
        // Must provide an identifier. 
        if(!$this->identifier)
            return false;
        
        // Generate Query
        $this->deleteArrayQuery();

        $this->result = mysql_query($this->query);
//        $this->debug();
        return $this->result;
    }
    
    public function countAll(){
        $this->countAllQuery();
        $this->result = mysql_query($this->query);
        $row = mysql_fetch_assoc($this->result);
        return $row['pizzadbtotal'];
    }
    
    public function fetch() {
        return mysql_fetch_array($this->result);
    }
    
    
    public function numAffectedRows() {
        return mysql_affected_rows();
    }
    
    /**
     * Returns number of returned-rows after selectArray()
     * @return int 
     */
    public function numReturnedRows() {
        return mysql_num_rows($this->result);
    }

    /**
     * Converts MySQL's date/time variables to UNIX timestamp
     * @param string $sqltime MySQL's date/time 
     * @return string  UNIX timestamp
     */
    function time_sqlTime2unix($sqltime) {
        return strtotime($sqltime . " GMT");
    }
  
    function time_unix2sqlTime($unixtime) {
        return gmdate("Y-m-d H:i:s", $unixtime);
    }
    
    public function startTransaction(){
        mysql_query("SET AUTOCOMMIT=0");
        mysql_query("START TRANSACTION");
    }
    
    public function commit(){
         mysql_query("COMMIT");
         mysql_query("SET AUTOCOMMIT=1");
    }
    
    public function rollback(){
        mysql_query("ROLLBACK");
        mysql_query("SET AUTOCOMMIT=1");
    }
    
    public function escape($textToEscape) {
        return mysql_real_escape_string($textToEscape);
    }
    
}
?>