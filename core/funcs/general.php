<?php

/* * ***** ****** ****** ****** ****** ******
 *
 * Author       :   Shafiul Azam
 *              :   ishafiul@gmail.com
 *              :   Project Manager
 * Page         :
 * Description  :   Global helper Functions
 * Last Updated :
 *
 * ****** ****** ****** ****** ****** ***** */

/**
 * @file
 * \brief General-purpose Global functions
 * 
 */


/**
 * @name URL/location Generator
 */
//@{

/**
 * Generate internal URLs to use in your PHPizza site
 * 
 * You should always use this functions to create URLs for using in your site!
 * 
 * Example:
 * \code
 * // If you have a controller at "user/login" location, you can get it's URL by calling:
 * $url = url('user/login');
 * // Then $url will contain something like: "http://yoursite.com/user/login" - based on your configuration.
 * // You can safely use this URL to link to your other internal pages.
 * \endcode
 * 
 * @param string $url 
 * @return string URL that works in your site
 */
function url($url) {
    $urlArr = explode("?", $url, 2); // Split based on the query string
    if(NICE_URL_ENABLED){
        $queryString = (isset($urlArr[1])) ? ( "?" . $urlArr[1]) : ("");
        return BASE_URL . $urlArr[0] . URL_EXTENTION . $queryString;
    }else{
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
function url_static($url) {
    $urlArr = explode("?", $url, 2); // Split based on the query string
    if(NICE_URL_ENABLED){
        $queryString = (isset($urlArr[1])) ? ( "?" . $urlArr[1]) : ("");
        return BASE_URL . 'static/' . $urlArr[0] . URL_EXTENTION . $queryString;
    }else{
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
 * $filePath = filePath('a/b.png');
 * 
 * // $filePath will contain something like http://yoursite.com/files/a/b.png
 * \endcode
 * 
 * @param string $filePath  location to the file. This file should exist in files directory
 * @return string - usable URL to the file
 */
function filePath($filePath) {
    // Returns absolute path for a file.
    return FILES_URL . $filePath;
}



//@}

/**
 * @name Quick %HTML generators
 */
//@{

/**
 * %HTML < a > tag generator - use this function to generate hyperlinks. 
 * 
 * 
 * @param string $url - controller's location.
 * @param string $text -  text to display for this link
 * @return string | generated html for the hyperlink tag
 * 
 * Example:
 * \code
 * // Say, you've a controller at "CONTROL/user/login" location.
 * $link = anchor('user/login', 'Click here to Login!');
 * // $link will contain something like: 
 * <a href="http://example.com/user/login">Click here to Login!</a> 
 * // based on your URL related configuration.
 * \endcode
 */

function anchor($url, $text) {
    return '<a href = "' . url($url). '">' . $text . '</a>';
}

/**
 * Generates a link with text $text , when clicked, a pop-up <i>Confirmation Window</i> will appear showing $confirmationMessage 
 * 
 * With buttons "Yes" and "No".
 * 
 * If user clicks <i>yes</i>, visitor will be redirected to $url
 * 
 * @param string $url
 * @param string $text
 * @param string $confirmationMessage
 * @return string - generated %HTML. 
 */
function confirmAndGo($url, $text, $confirmationMessage) {
    $url = url($url);
    return '<a href = "#" onclick ="if(confirm(\'' . $confirmationMessage . '\')){window.location=\'' . $url . '\'}">' . $text . '</a>';
}

/**
 * %HTML < img > tag generator.
 * 
 * @param string $url - path of the image. The image should reside in <b>files</b> directory.
 * @param mixed $attrArr - key value pair of Tag Attributes 
 * @return string - Generated %HTML for the image file.
 * 
 * Example:
 * \code
 * // Say, you have an image in "files/images/photo.png" 
 * $myImg = img('images/photo.png', array(
 *  'border' => 0, 'alt' => 'my photo'
 * ));
 * // Will produce something like:
 * <img src='yoursite.com/files/images/photo.png' border="0" alt="my photo" /> 
 * // based on your URL-related configuration.
 * \endcode
 */
function img($url, $attrArr=null) {
    $attrText = '';
    if ($attrArr) {
        foreach ($attrArr as $k => $v)
            $attrText .= "$k = '$v' ";
    }
    $str = '<img ' . $attrText;
    $str .= ' src ="' . filePath($url) . '" />';
    return $str;
}

/**
 * %HTML <a> tag generator for static (VIEW only, no constructor) pages
 * @param string $url "relative" URL of the path
 * @param string $text text to display for this link
 * @return string | generated html 
 */
function anchor_static($url, $text) {
    return "<a href = '" . url_static($url). "'>$text</a>";
}


/**
 * Generates %HTML to redirect to another page
 * 
 * @param string $page  URL to which the page will be redirected. 
 *  -  If you leave this empty - the page will be redirected to LANDING_PAGE
 * 
 * @param bool $byHeader  -  if true, redirection done by %HTML header. else, by javascript. 
 * 
 * \code
 * // You should provide FULL URL, which means if you want to redirect to an internal page "path/to/redirect" , you should use: 
 * redirect(url('path/to/redirect')); 
 * // note the use of url() function to generate FULL URL!!
 * \endcode
 * 
 * \note Execution of current page will terminate at the end of this function & redirected page will be brought.
 */
function redirect($page='', $byHeader = true) {
    if (empty($page))
        $page = url(LANDING_PAGE);
    
    if ($byHeader) {
        header('Location: ' . $page);
    } else {
        echo '<script>window.location = "$page";</script>';
    }
    exit();
}

//@}

/**
 * Get an instance of view object, to use inside your template files
 */

function getView(){
    global $__viewInstance;
    return $__viewInstance;
}


/**
 * @name Utility Functions
 */

//@{

/**
 * Safely returns value of a key from a key-value pair array. If the $index or key is not available, returns
 *  boolean false.
 * @param array $arr - key-value pair array.
 * @param mixed $index - inedx/key of an array element.
 * @return mixed
 *  - value if $index is set
 *  - boolean false if $index is unset 
 */

function arrVal($arr, $index){
    if(isset($arr[$index]))
        return $arr[$index];
    else
        return '';
}

//@}


?>