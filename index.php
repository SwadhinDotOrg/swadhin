<?php

// Run some custom users-scripts
require dirname(__FILE__) . '/pre_script.php';

// Load Configuarations
require dirname(__FILE__) . '/config.php';
require dirname(__FILE__) . '/userconfig.php';

// Set up Include Paths

set_include_path(implode(PATH_SEPARATOR, array(
            realpath(CUSTOM_DIR . 'class/'),
            realpath(CORE_DIR . 'class/'),
            get_include_path(),
        )));

// Load Core Class
require 'core.php';

// Start PHPizza
$phpizza = new PHPizza();
$__viewInstance = null; ///< A Global instance

$page = $phpizza->validate->input('p', "", false, LANDING_PAGE);

// Remove trailish slash
$page = rtrim($page, "/");

// Load model, view & controller
$phpizza->loadMVC($page);

// Run some custom user-script.
require dirname(__FILE__) . '/post_script.php';
?>

