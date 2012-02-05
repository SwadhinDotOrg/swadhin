<?php


/**
 * Error & Exception Handler
 */

class Error{
    
    public function exceptionHandler($e) {
        echo 'PizzaError: ' . $e->getMessage();
    }

    public function errorHandler($errno, $errstr, $errfile, $errline) {
        
        $html = new Html();
        $html->title = 'Oops!';
        $html->startBody();
        
        $errorMsg = 'Error ' . $errno . ': ' . $errstr . ' <br />in ' . $errfile . ' (Line ' . $errline . ')';
        echo $errorMsg;
        echo '<br /><br />';
        
        if(function_exists('xdebug_print_function_stack')){
            ini_set('xdebug.trace_format', 2);
            
            echo '<textarea cols="150" rows = "20">';
            xdebug_print_function_stack();
            echo '</textarea>';
        }else{
            debug_print_backtrace();
            echo '<br /><br />';
            echo HTML::a('http://xdebug.org/', 'You can optionally install XDebug!');
        }
        
        $html->done();
    }
}

?>
