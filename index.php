<?php


define('TESTING_PHPIZZA', true);    // Set true when unit testing PHPizza. Must be turned off in all other cases.

require dirname(__FILE__) . '/pre_script.php';
// Load Configuaration
require dirname(__FILE__) . '/config.php';
require dirname(__FILE__) . '/userconfig.php';
// Load Core Class
require CORE_DIR . 'class/Core.php';

// Start PHPizza
$phpizza = new PHPizza();
$__viewInstance = null; ///< A Global instance

$page = $phpizza->validate->input('p', "", false, LANDING_PAGE);

// Remove trailish slash
$page = rtrim($page,"/");
// Load model, view & controller
$phpizza->loadMVC($page);
require dirname(__FILE__) . '/post_script.php';
?>

