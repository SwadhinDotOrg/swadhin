<?php

class Controller extends CoreController{
    function __construct($core) {
        parent::__construct($core);
    }
    
    function index(){
        $this->data('title', 'PlainClean Theme');
        // Change the template
        $this->template('plainclean');
        $this->loadView('examples/hello');
    }
}
