<?php

class Response {

    const RESPONSE_TYPE_HTML = 0;
    const RESPONSE_TYPE_JSON = 1;
    const RESPONSE_TYPE_PLAINTEXT = 2;
    const RESPONSE_TYPE_XML = 3;
    
    public $core;                           ///< Reference to core Needed by Views
    public $responseType;                   ///< Response type e.g. HTML/JSON/Plain Text etc.
    
    public $themeName = null;
    public $themeLayoutFile = null;
    
    public $viewIsStatic = false;
    public $controllerLoaded = false;
    public $dataForView = array();

    /** @var Loader */
    public $loader = null;
    
    private $viewName;                      ///< Name (location) of the view to load
    public $error = null;
    
    private $outputStr = '';     ///< Final string that gets output

    function __construct($viewName=null, $resonseType = self::RESPONSE_TYPE_HTML) {
        $this->viewName = $viewName;
        $this->responseType = $resonseType;
        $this->loader = new Loader();
    }

    /**
     * Generates Output 
     */
    function output() {
        try {
            // Special care for typical View Loading
            $this->generateViewObject();
            if($this->viewName){
                ob_start();
                $this->loader->loadTemplate($this->themeName, $this->themeLayoutFile);
                $this->outputStr = ob_get_contents();
                ob_end_clean();
            }
            // actually sends output
            $this->sendHttpOutput();
            
        } catch (Exception $e) {
            // We should probably build response with appropriate error codes. 
            // For now, just throw Exception for default handling
            throw new Exception('Response Error: ' . $e);
        }
    }
    
    /**
     * 
     */
    private function sendHttpOutput(){
        echo $this->outputStr;
    }

    /**
     * Creates an object of the View class.
     * - You should NEVER call this function! As this function is automatically called by the framework. 
     */
    private function generateViewObject() {
        if ($this->viewName) {
            $viewOb = new View($this->core);
            // create a global instance
            View::$instance = $viewOb;
            // Check static permission
            if ($this->viewIsStatic && !$viewOb->__staticLoadAllowed) {
                throw new Exception('Error: Loading this page statically is denied.');
            }
            
            // Set the template: important
            $viewOb->template = $this->themeName;
            
            // Pass data set from controller
            if (!empty($this->dataForView)) {
                foreach ($this->dataForView as $varName => $varValue) {
                    $viewOb->$varName = $varValue;
                }
            }
        } else {
            // Check if controller loaded
            if (!$this->controllerLoaded) {
                // Invalid request. report 404
                throw new Exception('Error 404 Page not found!');
            }
//            $this->debug("View Not Loaded");
        }
    }

}
