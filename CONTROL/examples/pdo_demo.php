<?php

class Controller extends CoreController{
    
    /**
     *
     * @var UserModel_prepared 
     */
    private $model;
    
    function __construct($core) {
        parent::__construct($core);
        $this->model = $this->loadModel('ExamplesModel_prepared');
        $this->data('title', 'PDO Testing');
    }
    
    function index(){
        // select all from db
        
        $all = $this->model->selectAll();
        $this->data('all', $all);
        $this->loadView();
    }
    
    function select(){
        $this->index();
    }
    
    function insert(){
        // Let's randomly generate data and make 2 inserts.
        $this->model->randomInsert();
        $this->index();
    }
    
    function update(){
        $this->model->updateDate();
        $this->index();
    }
}
