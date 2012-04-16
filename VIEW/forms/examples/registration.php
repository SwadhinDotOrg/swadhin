<?php

/* * ***** ****** ****** ****** ****** ******
 *
 * Author       :   Shafiul Azam
 *              :   ishafiul@gmail.com
 *              :   Project Manager, 
 *              :   PROGmaatic Developer Network
 * Page         :
 * Description  :   
 * Last Updated :
 *
 * ****** ****** ****** ****** ****** ***** */

class ExamplesRegistration extends CoreForm {
    public function __construct($core) {
        // MUST call parent's constructor
        parent::__construct($this, $core);
        // URL where form processing will be done!
        $this->action = LibUrl::url('examples/registration/submit');
        // Do some CSS tweaking!
        $this->submitButtonText = "Register!";
        $this->tableCellSpacing = "10px";
        // Create some validators to be applied on User submitted data.
        $this->validators =array(
            'username' => array('Username', "limit,5,7"),
            'passwd' => array('Password', 'required|limit,,2'),
            'passwd2' => array('Retype password', 'equalsToElement,passwd'),
            'email' => array('Email', 'limit,3|email'),
            'sex' => array('Sex', 'enum,male:female')
        );
        
    }
    
    public function createElements() {
        
        $elements = array();
        
        $elements['username'] = LibForm::input();
        $elements['passwd'] = LibForm::password();
        $elements['passwd2'] = LibForm::password();
        $elements['email'] = LibForm::input();
        
        $options = array(
            "male" => "Male",
            "female" => "Female"
        );
        
        $elements['sex'] = LibForm::dropdown($options, 'female');
        
        $this->setElements($elements);
    }
}