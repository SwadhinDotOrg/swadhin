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

        if (isset($_GET['p']))
            $this->swadhin->requestedPage = $_GET['p'];
        else
            $this->swadhin->requestedPage = Config::LANDING_PAGE;

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
        Config::initConfigurations();
        // Load user-specific constants
        require $this->sourceDir . '/userconfig.php';
        User_config::initConfigurations();
    }

    private function setIncludePaths() {
        set_include_path(implode(PATH_SEPARATOR, array(
                    realpath(Config::$custom_classes_dir),
                    realpath(Config::$core_classes_dir),
                    get_include_path(),
                )));
    }

    private function includeSourceFiles() {
        // Load Boostrap
        require 'swadhin.php';
    }

}

?>
