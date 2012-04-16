<?php

class Swadhin {

    public $sourceDir;                  ///<    Directory where the project is located. It's the direcotry where index.php file was found.
    public $requestedPage = null;       ///<    The Requested page

    /** @var Core */
    private $core;

    /** @var Error */
    protected $errorHandler;

    /**  @var CoreView */
    public $view;

    /** @var Config */
    public $config;

    /**
     * Constructor
     * @param string $sourceDir 
     */
    function __construct($sourceDir) {
        $this->sourceDir = $sourceDir;
    }

    /**
     * Starts Swadhin Framework 
     */
    public function start() {
        // Validate Request
        $this->validateRequest();

        // Initialize Environment
        $this->init();

        // Load model, view & controller
        $this->loadMVC();
    }

    /**
     * Initializes environment for the framework to work 
     */
    private function init() {
        $this->includeSourceFiles();

        // Set up autoload function
        spl_autoload_register(array($this, 'autoloadHandler'));

        // Create object for Error/Exception Handler
        $this->errorHandler = new Error();

        // Set Handlers
        set_exception_handler(array($this->errorHandler, 'exceptionHandler'));
        set_error_handler(array($this->errorHandler, 'errorHandler'), (E_ALL | E_STRICT) & ~E_NOTICE);

        // Create configurations

        if (!$this->config)
            $this->config = new Config();

        // Create instance of Core
        $this->core = new Core($this->config);
        // Refer to View Instance
        $this->view = $this->core->view;
    }

    /**
     * Includes manually necessary source files. 
     * This is necessary since We have not registered our autoloaders yet.
     */
    private function includeSourceFiles() {
        require 'core.php';
//        require 'error.php';
//        require 'html.php';
    }

    /**
     * Loads Model, View & Controller for current request
     */
    public function loadMVC() {
        $this->core->loadMVC($this->requestedPage);
    }

    /**
     *
     * @param type $classname 
     */
    public function autoloadHandler($classname) {
        $path = strtolower(preg_replace('/([a-z])([A-Z])/', '$1/$2', $classname));
//        echo 'Trying to ' . $classname . ' |FOR| ' . $path . '<br />';
        require $path . '.php';
    }

    /**
     *  Validate Request made by the user 
     */
    private function validateRequest() {
        if (!$this->requestedPage)
            $this->requestedPage = Config::LANDING_PAGE;

        $this->requestedPage = rtrim($this->requestedPage, "/");
    }

}
