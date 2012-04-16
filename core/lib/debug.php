<?php

/**
 * @name Helpful functions for debugging/Printing 
 */

class LibDebug {

    /**
     * Safely returns value of a key from a key-value pair array. If the $index or key is not available, returns
     *  boolean false.
     * @param array $arr - key-value pair array.
     * @param mixed $index - inedx/key of an array element.
     * @return mixed
     *  - value if $index is set
     *  - boolean false if $index is unset 
     */
    static function arrVal($arr, $index, $returnValue = '') {
        if (isset($arr[$index]))
            return $arr[$index];
        else
            return $returnValue;
    }

}
