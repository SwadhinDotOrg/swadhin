<?php

class Swadhin {

    public $sourceDir;      ///<    Directory where the project is located. It's the direcotry where index.php file was found.

    function __construct($sourceDir) {
        $this->sourceDir = $sourceDir;
    }

    public function start() {
        // Initialize Environment
        $this->init();

        // Start PHPizza
        $phpizza = new PHPizza();
        $page = $phpizza->validate->input('p', "", false, LANDING_PAGE);

        // Remove trailish slash
        $page = rtrim($page, "/");

        // Load model, view & controller
        $phpizza->loadMVC($page);
    }

    private function init() {
        $this->includeSourceFiles();
    }



    private function includeSourceFiles() {
        // Load Core Class
        require 'core.php';
    }

}
