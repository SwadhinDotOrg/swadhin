<?php
/* * ***** ****** ****** ****** ****** ******
 *
 * Author       :   Shafiul Azam
 *              :   ishafiul@gmail.com
 *              :   Project Manager
 * Page         :
 * Description  :   
 * Last Updated :
 *
 * ****** ****** ****** ****** ****** ***** */

class View extends Template {

    public function __construct($core) {
        // Must call parent's constructor
        parent::__construct($core);
        // Set titles & other attributes here
        // For demonstration, we're using the value set by the controller
        //  Get the instance
        $this->setStatic();
        $this->heading = "Oops! Something went wrong... | PHPizza";
    }

    public function mainContent() {
        // This function must be implemented!
        // now follows html:
        ?>
        <h1 style="color:red;">Oops! Something went wrong!</h1>
        <br /><br />
        Something unexpected occurred, maybe the page does not exist anymore. 
        <br /><br />
        We are extremely sorry for the inconveniences. We have logged the 
        error and technical team will response to it. 
        <?php
    }

}
