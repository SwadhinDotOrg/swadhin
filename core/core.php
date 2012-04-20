<?php

/** \brief The Most important class - makes the framework working! 
 * 
 * @author Shafiul Azam
 * This class contains everything you need. An object of this class, $core (global variable) is created
 * for you. Within controllers, views & templates, you can get access to this class by this code:
 * global $core;
 * In fact, this $core variable is the only thing you need. See it's member variables
 */
class Core {

    /** @var Funcs */
    public $funcs;  ///<    An object of class: Funcs
    
    /** @var Validator */
    public $validate;   ///<    An object of class:  Validator 
    
    /** @var View */
    public $view;   ///<    An object of class: CustomView
    
    /** @var Controller */
    public $controller; ///<    An object of class: Controller
    
    /** @var Loader */
    public $loader;
    
    public $page;   ///<    cotains "The Page" - see documentation for more details
    public $controllerFunctionToCall; ///<  contains the name of the function of constructor to call.
    public $formData;   ///<    Key-value Array for containing HTML strings for web forms
    
    // Others
    public $themeName;  ///<    Name of the template. This must be name of the template's folder under "templates" directory.
    public $themeLayoutFile;   ///< name of the file to load under theme's directory. Default value is "index.php". You can change it before calling loadView()
    
    public $isStatic; ///< true if the page is static: no controller to load, view automatically called.
    
    // Load staus
    public $controllerLoaded = false;   ///<    Boolean, true if controller class loaded.
    public $viewName = null;         ///<    Name of the view

    // vars for internal use. Don't use/depend on any of these in your code
    public $coreDir;
    public $autoloadedData;
    private $__version;                 ///< Core Version
    private $__dbconfig;                ///< Database Credentials
    // Internal
    private $oneModelLoaded = false;    ///< Whether CoreModel & DB driver already loaded
    
    private $responseType = null;

    /**
     * Constractor.
     * - Initializes member variables
     * 
     * @return None
     */
    public function __construct() {
        $this->__version = "1.3.1 beta";
        // Acquire Database Configuration
        $this->__dbconfig = Config::$db;
        // Create other members
        $this->loader = new Loader();
        $this->funcs = new Funcs($this);
        $this->validate = new Validator($this);
        // Set some default characteristics
        $this->isStatic = false;
        // init some member vars
        $this->formData = array();
        // Set site template
        $this->themeName = Config::SITE_THEME;  //  Can load from DB too.
        $this->themeLayoutFile = Config::THEME_LAYOUT_FILE;
        // Set up internal vars
        $this->coreDir = Config::$core_classes_dir;
    }



    /**
     * Returns current PHPizza Version
     * @return string
     */
    public function getVersion() {
        return $this->__version;
    }

    /* Loaders */

    /**
     * Use this function to Load a Model class. You can load any number of model classes by calling this function 
     * many times.
     * 
     * @param string $model name of the Model class. This class must reside under MODEL directory.
     * \note If your model class is located in "MODEL/camel/case/class.php" use "CamelCaseClass" as this argument. Class name should be also "CamelCaseClass" 
     * @return object of newly loaded model.
     */
    public function loadModel($model) {
        $filename = Config::$models_dir . strtolower(preg_replace('/([a-z])([A-Z])/', '$1/$2', $model)) . '.php';
        require_once $filename;
        $tempVar = explode('/', $model);
        $className = end($tempVar);
        $var = new $className($this);
        return $var;
    }

    /**
     * Use this function where appropriate (maybe within Template class or in your VIEW classes) to load
     * the "%HTML Blocks" - you can find some sample classes in GeneralLinks and FormLinks classes.
     * @param string $block name of the file. This file must reside in VIEW/blocks/ directory. 
     * @return object of newly created block.
     */
    
    public function loadBlock($block) {
        $filename = Config::$views_dir . 'blocks/' . strtolower(preg_replace('/([a-z])([A-Z])/', '$1/$2', $block)) . '.php';
        require $filename;
        $tempVar = explode('/', $block);
        $className = end($tempVar);
        $var = new $className($this);
        return $var;
    }

    /**
     * This function loads a Controller class. 
     * - You should NEVER call this function! As this function is automatically called by the framework.
     * 
     * @param string $controller name of the controller class
     */
    private function loadController($controller) {
        $filename = Config::$controllers_dir . $controller . '.php';
        $this->controllerLoaded = true;
        if (file_exists($filename)) {
            require $filename;
            // Also, generate the controller object & call "functionToCall"
            $this->generateControllerObject();  // Controller object generated
        } else {
            throw new Exception('Error 404 - Controller ' . $controller . ' Not Found!');
        }
    }

    /**
     * Use this function to Load a View class. You should load ONLY ONE view class. Loading more than one View class will result in error!
     * 
     * Call this function within your constructor EXPLICITLY. If you forget to call this function, No VIEW will be loaded!
     *  - However, this function is automatically called by the Core if user is requesting some "static" page (page with no controller).
     * @param string $view name of the View class. This class must extend Template class & reside under VIEW/pages/ directory.
     *  - if you don't pass the parameter, default view for the page gets loaded.
     */
    public function loadView($view='', $responseType = Response::RESPONSE_TYPE_HTML) {
        if (empty($view))
            $view = $this->page;
        
        $this->responseType = $responseType;
        $this->viewName = $view;
        $loadResult = $this->loader->__loadView($view, $this->themeName);
        
        if ($this->isStatic && !$loadResult) {
            throw new Exception('Error: Could not load the static page.');
        }
    }


    /** 
     * @name Functions for Internal Use
     * These functions are used internally by the framework.
     * - You should NEVER call these functions! As they are automatically called by the framework.
     */
    
    //@{

    /**
     * Echoes the string provided as parameter $str if DEBUG_MODE is set true.
     * @param string $str the string to echo.
     */
    public function debug($str) {
        if (Config::DEBUG_MODE)
            echo '<pre>' . $str . '</pre>';
    }

    /* Useful functions for index page */

    /**
     * Used to load the default controller.
     * 
     * It also calls generateViewObject() followed by loadTemplate() to completely generate %HTML output.
     * 
     * Warning:
     * - You should NEVER call this function! As this function is automatically called by the framework.
     * @param string $page "The Page"
     * @return None
     */
    public function loadMVC($page) {
        $this->findPage($page);
        // Autoloading
        $this->autoloadFromConfig();
        // Load Controllers/Views
        if ($this->isStatic) {
            // No controller. Load view
            $this->loadView();  //  Default view is loaded
        } else {
            // Load Controller.
            $this->loadController($this->page);
        }
        
        // Output Response
        $response = new Response($this->viewName, $this->responseType);
        
        $response->core = $this;    // Needed by Views
        $response->viewIsStatic = $this->isStatic;
        $response->controllerLoaded = $this->controllerLoaded;
        
        if($this->controllerLoaded)
            $response->dataForView = $this->controller->__data;
        
        $response->themeName = $this->themeName;
        $response->themeLayoutFile = $this->themeLayoutFile;
        
        $response->output();
    }

    // Controller related

    /**
     * Creates object of the Controller class. Also calls the function specified by "functionToCall"
     * - You should NEVER call this function! As this function is automatically called by the framework. 
     */
    public function generateControllerObject() {
        $this->controller = new Controller($this);
        if (method_exists($this->controller, $this->controllerFunctionToCall))
            call_user_func(array($this->controller, $this->controllerFunctionToCall));
        else {
            $this->fatal('Error : Requested controller-method not found!');
        }
    }

    // Utility Functions

    /**
     * Used internally to find out the "page" & "functionToCall"
     * - THIS IS USED INTERNALLY, NEVER CALL THIS FUNCTION! 
     * @param string $URL the query string user provided
     * @return None 
     */
    private function findPage($URL) {
        $pageArr = explode('/', $URL);
        $numSegments = count($pageArr);

        if ($numSegments == 1 && $pageArr[0] != 'static') {
            $this->page = $URL;
            $this->controllerFunctionToCall = Config::DEFAULT_CONTROLLER_FUNCTION;
        } else {
            // Handle static pages first.
            if ($pageArr[0] == 'static') {
                // STATIC: No controller here.
                $this->isStatic = true;
                unset($pageArr[0]);
                $this->page = implode('/', $pageArr);
            } else {
                // DYNAMIC
                // Check if full path exists
                $controllerPath = Config::$controllers_dir . $URL . '.php';
                if (file_exists($controllerPath)) {
                    $this->page = $URL;
                    $this->controllerFunctionToCall = Config::DEFAULT_CONTROLLER_FUNCTION;
                } else {
                    // Check later.
                    $this->controllerFunctionToCall = $pageArr[$numSegments - 1];
                    unset($pageArr[$numSegments - 1]);
                    $this->page = implode('/', $pageArr);
                }
            }
        }
//        $this->debug("Page: " . $this->page . " FunctionToCall: " . $this->functionToCall);
    }

    /**
     *
     * @global array $PHPizza_autoload 
     */
    private function autoloadFromConfig() {
        // classes
        if (!empty(Config::$autoloads[Config::AUTOLOAD_CLASS])) {
            foreach (Config::$autoloads[Config::AUTOLOAD_CLASS] as $className) {
//                require Config::$custom_classes_dir . 'class/' . strtolower(preg_replace('/([a-z])([A-Z])/', '$1/$2', $className)) . '.php';
                $this->autoloadedData[Config::AUTOLOAD_CLASS][$className] = new $className($this);
            }
        }
        // MODELS
        if (!empty(Config::$autoloads[Config::AUTOLOAD_MODEL])) {
            $this->oneModelLoaded = true;
            // Include all models
            foreach (Config::$autoloads[Config::AUTOLOAD_MODEL] as $className) {
                require Config::$models_dir . strtolower(preg_replace('/([a-z])([A-Z])/', '$1/$2', $className)) . '.php';
                $this->autoloadedData[Config::AUTOLOAD_MODEL][$className] = new $className($this);
            }
        }
    }

    public function getPage() {
        return $this->page;
    }

    //@}

    /**
     * Generate FATAL Errors - terminates execution immediately printing the error message.
     * @param type $msg 
     */
    public function fatal($msg, $triggerError=true) {
        throw new Exception($msg);
    }

    /**
     * Returns an instance of Database driver object
     * @return object - implementation of GenericDB i.e. MySQL
     */
    public function getDb() {
        $driver = 'Db' . ucfirst($this->__dbconfig['driver']);
        $db = new $driver($this->__dbconfig);
        return $db;
    }

    /**
     * Get instance of an autoloaded class. This can be autoloaded Custom Class or Model
     * @param type $className
     * @param string $type - This can be any of 'custom' or 'model' or 'func'
     * @return object - instance of the autoloaded class if successful. FALSE otherwise 
     */
    public function autoload($className, $type = Config::AUTOLOAD_CLASS) {
        return (isset($this->autoloadedData[$type][$className])) ? ($this->autoloadedData[$type][$className]) : (false);
    }

}
