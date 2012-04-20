<?php

class Loader {

    /**
     * \warning Do not call this function! Call CoreController::loadView() instead. This method is used internally. UPDATE DOCUMENTATION FOR DEVELOPERS
     * 
     * Use this function to Load a View class. You should load ONLY ONE view class. Loading more than one View class will result in error!
     * 
     * @param string $view name of the View class. This class must extend Template class & reside under VIEW/pages/ directory.
     * 
     */
    public function __loadView($view, $themeName) {
        require Config::$themes_dir . $themeName . '/template.php';
        // Load the specific VIEW class.
        $filename = Config::$views_dir . 'pages/' . $view . '.php';
//        die('requiring' . $filename);
        return $this->safelyLoad($filename);
    }

    /**
     * Loads necessary files (mainly index.php) from templates/<SELECTED THEME> folder.
     * \warning You should NEVER call this function! As this function is internally called by the framework. 
     */
    public function loadTemplate($themeName, $themeLayoutFile) {
        $fileToLoad = Config::$themes_dir . $themeName . '/' . $themeLayoutFile;
        require $fileToLoad;
    }

    /**
     * This function is used to safely load a php file. you can use it instead of require_once()
     * @param string $filename name of the file to load
     * @return bool true if file found, false otherwise. 
     */
    public function safelyLoadOnce($filename) {
        if (file_exists($filename)) {
            require_once $filename;
            return true;
        } else {
            return false;
        }
    }

    public function safelyLoad($filename) {
        if (file_exists($filename)) {
            require $filename;
            return true;
        } else {
            return false;
        }
    }

}