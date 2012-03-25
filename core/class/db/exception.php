<?php

class DbException extends Exception{
    
    public $query = 'Not Available';
    public $errNo = '';
    public $errMsg = '';
    
    public function __construct($errNo, $errMsg, $query) {
        $message = '[' . $errNo . '] ' . $errMsg;
        parent::__construct($message);
        $this->query = $query;
        $this->errNo = $errNo;
        $this->errMsg = $errMsg;
    }
}
