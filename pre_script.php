<?php

/**
 * @file
 * \brief Custom scripting to run before %PHPizza starts running
 * 
 * You may write your own codes here which you want to run before the 
 * framework starts running.
 * 
 * Codes in this page are executes before anything has started!
 */


// Code beyond this line is totally optional.

error_reporting(E_ALL);             // Turning on error reporting
ini_set('display_errors', '1');



//ini_set('xdebug.auto_trace', 1);

define('TESTING_PHPIZZA', false);    // Set true when Unit Testing PHPizza. Must be set to FALSE in all other cases!

if(TESTING_PHPIZZA && !session_id())
    session_start();


// Calculation of time taken to generate the page
// ***************************************************************************
// comment out this block in production server
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$pizzaStartTime = $time;
// ***************************************************************************
// Time Calculation Started.
?>