<?php

/**
 * \brief Helper functions for generating URLs 
 */
class LibUrl {

    /**
     * Generate internal URLs to use in your site
     * 
     * You should always use this functions to create URLs for using in your site!
     * 
     * Example:
     * \code
     * // If you have a controller at "user/login" location, you can get it's URL by calling:
     * $url = LibUrl::url('user/login');
     * // Then $url will contain something like: "http://yoursite.com/user/login" - based on your configuration.
     * // You can safely use this URL to link to your other internal pages.
     * \endcode
     * 
     * @param string $url 
     * @return string URL that works in your site
     */
    static function url($url) {
        $urlArr = explode("?", $url, 2); // Split based on the query string
        if (NICE_URL_ENABLED) {
            $queryString = (isset($urlArr[1])) ? ( "?" . $urlArr[1]) : ("");
            return BASE_URL . $urlArr[0] . URL_EXTENTION . $queryString;
        } else {
            $queryString = (isset($urlArr[1])) ? ( "&" . $urlArr[1]) : ("");
            return BASE_URL . '?p=' . $urlArr[0] . $queryString;
        }
    }

    /**
     * Generates URL for "static" - (VIEW only, no constructor) pages of your site. You should use this 
     * function to generate URLs for the pages that have no constructor for them.
     * 
     * @param string $url 
     * @return string URL that works in your site 
     */
    static function url_static($url) {
        $urlArr = explode("?", $url, 2); // Split based on the query string
        if (NICE_URL_ENABLED) {
            $queryString = (isset($urlArr[1])) ? ( "?" . $urlArr[1]) : ("");
            return BASE_URL . 'static/' . $urlArr[0] . URL_EXTENTION . $queryString;
        } else {
            $queryString = (isset($urlArr[1])) ? ( "&" . $urlArr[1]) : ("");
            return BASE_URL . '?p=static/' . $urlArr[0] . $queryString;
        }
    }

    /**
     * Generates URL to use as location for files - the file should reside in <b>files</b> directory (
     * you can use sub-directories inside the <i>files</i> direcotry)
     * 
     * You can use this generated URL safely to access the file through the web.
     * 
     * Example: If you have a file, say, <i>files/a/b.png</i> - you should call:
     * \code
     * $filePath = LibUrl::filePath('a/b.png');
     * 
     * // $filePath will contain something like http://yoursite.com/files/a/b.png
     * \endcode
     * 
     * @param string $filePath  location to the file. This file should exist in files directory
     * @return string - usable URL to the file
     */
    static function filePath($filePath) {
        // Returns absolute path for a file.
        return FILES_URL . $filePath;
    }

}
