<?php

/**
 * Starts the Framework to handle an HTTP request. 
 */
class Bootstrap {

    public $sourceDir;      ///<    Directory where the project is located. It's the direcotry where index.php file was found.

    /**  @var Swadhin */
    private $swadhin = null;

    /**
     * Constructor
     * @param string $sourceDir 
     */
    function __construct($sourceDir) {
        $this->sourceDir = $sourceDir;
    }

    private function preLoading() {
        // Code beyond this line is totally optional.
        // Turning on error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        //ini_set('xdebug.auto_trace', 1);

        define('TESTING_PHPIZZA', false);    // Set true when Unit Testing PHPizza. Must be set to FALSE in all other cases!

        if (TESTING_PHPIZZA && !session_id())
            session_start();
    }

    private function postLoading() {
        
    }

    function start() {
        // Run User-defined scripts
        $this->preLoading();
        // Initialize Environment
        $this->init();
        // Bootstrap the framework
        $this->swadhin = new Swadhin($this->sourceDir);
        $this->swadhin->requestedPage = $_GET['p'];
        $this->swadhin->start();

        // Run User-Defined Scripts
        $this->postLoading();
    }

    private function init() {
        $this->loadConstants();
        $this->setIncludePaths();
        $this->includeSourceFiles();
    }

    private function loadConstants() {
        // Load Configuarations
        require $this->sourceDir . '/config.php';
        // Load user-specific constants
        require $this->sourceDir . '/userconfig.php';
    }

    private function setIncludePaths() {
        set_include_path(implode(PATH_SEPARATOR, array(
                    realpath(CUSTOM_DIR . 'class/'),
                    realpath(CORE_DIR . 'class/'),
                    get_include_path(),
                )));
    }

    private function includeSourceFiles() {
        // Load Boostrap
        require 'swadhin.php';
    }

}

?>
