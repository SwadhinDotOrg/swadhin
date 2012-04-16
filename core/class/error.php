<?php

define('PRINT_TRACE_IN_TEXTAREA', true);

/**
 * Error & Exception Handler
 */
class Error {
    
    public function exceptionHandler($e) {
        $errorMsg = 'Uncaught Exception ' . $e->getCode() . ': <b style="color:red;">' . $e->getMessage() . '</b>
            <br /> 
            in <i>' . $e->getFile() . ' (Line ' . $e->getLine() . ')</i>';
        if(isset($e->query)){
            $errorMsg .= '<br /><br />';
            $errorMsg .= '<div style="background:yellow; padding:5px;">';
            $errorMsg .= 'Database Query: <b>' . $e->query . '</b>';
            $errorMsg .= '</div>';
        }
        if(DEBUG_MODE)
            $this->output('Uncaught Exception', $errorMsg);
        header ('Location: static/error');
    }

    public function errorHandler($errno, $errstr, $errfile, $errline) {
        $errorMsg = 'Error ' . $errno . ': <b style="color:red;">' . $errstr . '</b> 
            <br />
            in <i>' . $errfile . ' (Line ' . $errline . ') </i>';
        if(DEBUG_MODE)
            $this->output('Error', $errorMsg);
        header ('Location: static/error');
    }

    public function output($title, $msg) {

        $html = new Html();
        $html->title = $title . ' | PHPizza';
        $html->startBody();

        echo '<div>';
        echo $msg;
        echo '</div>';
        
        echo '<br /><br />';
        
        if(PRINT_TRACE_IN_TEXTAREA)
            echo '<textarea style="background:#FFF380; color:blue;" cols="150" rows = "20">';

        if (function_exists('xdebug_print_function_stack')) {
            ini_set('xdebug.trace_format', 2);
            xdebug_print_function_stack();
        } else {
            debug_print_backtrace();
        }

        if(PRINT_TRACE_IN_TEXTAREA)
            echo '</textarea>';

        $html->done();
    }

}