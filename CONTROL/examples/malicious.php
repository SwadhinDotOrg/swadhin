<?php


class Controller extends CoreController{
    function __construct($core) {
        parent::__construct($core);
    }
    
    function index(){
        $mc = new Malicious();
        $mc->getDbInfo();
    }
}
