<?php

// Constants

/**
 * \brief Generate %HTML strings easily
 * 
 * @author Shafiul Azam
 * 
 */
class Html {

    const MSGBOX_INFO = 0;
    const MSGBOX_SUCCESS = 1;
    const MSGBOX_WARNING = 2;
    const MSGBOX_ERROR = 3;

    public $title;      ///< Title of the document
    public $head;       ///< Any string you want to appear in the <head></head> section
    public $body;       ///< Any string you want to appear in the <body></body> section

    /**
     * @name %HTML Tag Generator Functions 
     */
    //{@

    /**
     * Generate a single Table Row ( <tr> element)
     * @param array $td_array | each element of the array should be a column for the Row (<td> element)
     * @return string | Generated html
     */
    public static function tr($td_array, $type = 'tr') {
        $str = '<' . $type . '>';
        foreach ($td_array as $i) {
            $str .= "<td>$i</td>";
        }
        $str .= '</' . $type . '>';
        return $str;
    }

    /**
     * Generates a complete %HTML Table
     * @param array $data - is an array of rows of the table. Each row is in turn another array of columns of that row.
     * @param array $attrArr - Associative array for %HTML attributes of the table
     * @param boolean $insertTh - if set to true, assumes first row of $data contains heading.
     * @return string Generated %HTML for that table.
     */
    public static function table($data, $attrArr = null, $insertTh = true) {
        $attrText = '';
        if ($attrArr) {
            foreach ($attrArr as $k => $v)
                $attrText .= "$k = '$v' ";
        }
        $str = '<table ' . $attrText . ' >';
        if ($insertTh) {
            foreach ($data[0] as $cell)
                $str .= '<th>' . $cell . '</th>';
            unset($data[0]);
        }
        foreach ($data as $row) {
            $str .= '<tr>';
            foreach ($row as $cell)
                $str .= '<td>' . $cell . '</td>';
            $str .= '</tr>';
        }
        $str .= '</table>';
        return $str;
    }

    /**
     * Generates %HTML for lists - ordered or unordered
     * @param array $items - Array elements are items of the list.
     * @param string $listType "ul" for Unordered (Bullet) list, "ol" for ordered (numbered) list.
     * @param array $attrArr Associative array: <i>key</i> contains the attribute and <i>value</i> contains the value for that attribute
     * @return Html | generated html 
     */
    public static function lists($items, $listType = "ul", $attrArr = null) {
        $attrText = "";
        if ($attrArr) {
            foreach ($attrArr as $k => $v)
                $attrText .= "$k = '$v' ";
        }
        $str = "<$listType $attrText> \n";
        foreach ($items as $i) {
            $str .= "\t <li>$i</li> \n";
        }
        $str .= "</$listType> \n";
        return $str;
    }

    /**
     * %HTML anchor: "< a >" tag generator - use this function to generate hyperlinks. 
     * 
     * 
     * @param string $url - controller's location.
     * @param string $text -  text to display for this link
     * @param boolean $isStatic - if set to true, assumes the $url is static link.
     * @param array $attrArr Associative array: <i>key</i> contains the attribute and <i>value</i> contains the value for that attribute
     * @return string | generated html for the hyperlink tag
     * 
     * Example:
     * \code
     * // Say, you've a controller at "CONTROL/user/login" location.
     * $link = Html::anchor('user/login', 'Click here to Login!');
     * // $link will contain something like: 
     * <a href="http://example.com/user/login">Click here to Login!</a> 
     * // based on your URL related configuration.
     * \endcode
     */
    static function anchor($url, $text, $isStatic = false, $attrArr = null) {
        if ($isStatic) {
            $url = LibUrl::url_static($url);
        } else {
            if (!preg_match('@^(https?|ftp)://@', $url))
                $url = LibUrl::url($url);
        }

        // Generate %HTML
        $attrText = "";
        if ($attrArr) {
            foreach ($attrArr as $k => $v)
                $attrText .= "$k = '$v' ";
        }
        return '<a ' . $attrText .' href = "' . $url . '">' . $text . '</a>';
    }

    /**
     * %HTML <a> tag generator for static (VIEW only, no constructor) pages
     * \warning This function will be removed in future versions! Use Html::anchor($url, $text, true) instead.
     * @param string $url "relative" URL of the path
     * @param string $text text to display for this link
     * @return string | generated html 
     */
    static function anchor_static($url, $text) {
        return self::anchor($url, $text, true);
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
    static function confirmAndGo($url, $text, $confirmationMessage) {
        $url = LibUrl::url($url);
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
     * $myImg = Html::img('images/photo.png', array(
     *  'border' => 0, 'alt' => 'my photo'
     * ));
     * // Will produce something like:
     * <img src='yoursite.com/files/images/photo.png' border="0" alt="my photo" /> 
     * // based on your URL-related configuration.
     * \endcode
     */
    static function img($url, $attrArr = null) {
        $attrText = '';
        if ($attrArr) {
            foreach ($attrArr as $k => $v)
                $attrText .= "$k = '$v' ";
        }
        $str = '<img ' . $attrText;
        $str .= ' src ="' . LibUrl::filePath($url) . '" />';
        return $str;
    }

    //@}

    /**
     * @name Form related
     */
    //@{

    /**
     * Generates html for an <input> element
     * @param string $name  name attribute
     * @param string $type  type attribute    
     * @param string $value value attribute
     * @param array $attrArr key-value array, key being the attribute and value being the value for that attribute
     * @return string | generated html 
     */
    public static function input($name, $type = "text", $value = "", $attrArr = null) {
        $attrText = "";

        if ($attrArr) {
            foreach ($attrArr as $k => $v)
                $attrText .= "$k = '$v' ";
        }
        return "<input type='$type' name = '$name' value='$value' $attrText />";
    }

    /**
     * Generates html for <select> element
     * @param string $name "name" attribute
     * @param array $options key-value array for <option> elements for this select element.
     *  - key is the label for the option
     *  - value is the "value" attribute for the option
     * @param string $selectedValue "value" attribute of the <option> which will be selected by default
     * @param array $attrArr key-value array, key being the attribute and value being the value for that attribute
     * @return string | generated html
     */
    public static function select($name, $options, $selectedValue = "", $attrArr = null) {
        $attrText = "";
        if ($attrArr) {
            foreach ($attrArr as $k => $v)
                $attrText .= "$k = '$v' ";
        }
        $str = "<select ";
        $str.= "$attrText name = '$name'";
//        var_dump($options);
//        print_r($selectedValue);
//        exit();
        if (is_array($selectedValue)) {
            $str .= ' multiple="multiple" >';
            foreach ($options as $value => $displayText) {
                $selected = (in_array($value, $selectedValue)) ? ("selected = 'selected'") : ("");
                $str .= "<option $selected name = '$value' value = '$value'>$displayText</option>";
            }
        } else {
            $str .= '>';
            foreach ($options as $value => $displayText) {
                $selected = ($value == $selectedValue) ? ("selected = 'selected'") : ("");
                $str .= "<option $selected name = '$value' value = '$value'>$displayText</option>";
            }
        }

        $str .= "</select>";
        return $str;
    }

    /**
     * Generates HTML for  <textarea>
     * @param string $name "name" attribute
     * @param string $value the value for this textarea: <textarea>$value</textarea>
     * @param array $attrArr key-value array, key being the attribute and value being the value for that attribute
     * @return string | generated html 
     */
    public static function textarea($name, $attrArr = null, $value = "") {
        $attrText = "";
        if ($attrArr) {
            foreach ($attrArr as $k => $v)
                $attrText .= "$k = '$v' ";
        }
        $str = "<textarea name = '$name' $attrText >$value</textarea>";
        return $str;
    }

    /**
     * A dummy form element creator. Takes all arguments like a regular html element creator 
     * but outputs nothing. May be suitable for some applications.
     * @param type $name
     * @param type $attrArr
     * @param type $value 
     */
    public static function dummy($name, $attrArr = null, $value = '') {
        // Does nothing!
    }

    //@}

    /**
     * Generates styled %HTML to display important messages
     * @param string $message the $HTML message to display
     * @param int $mode indicates status of the message
     *  - see constants in Funcs::messageExit() function
     * @param bool $exit if set to true, page dies (exits) after displaying the message
     * @param string $id ID attribute for the messagebox div
     * @return string | generated html
     */
    public static function msgbox($message, $mode = Html::MSGBOX_SUCCESS, $exit = false, $id = "") {
        // modes: 0: info, 1: success, 2: warning, 3: error
        $classes = array('msg_info', 'msg_success', 'msg_warning', 'msg_error');
        $str = '<div id = "' . $id . '" class="' . $classes[$mode] . '" >' . $message . '</div>';
        $str .= '<br />';
        return $str;
        if ($exit) {
            exit();
        }
    }

    /**
     * Call this function inside your code (controller or view) to create an %HTML div element - which user 
     * can hide or show by clicking a title.
     * 
     * @param string $title heading of the div, also a link to click to toggle visibility   
     * @param string $content   %HTML content of the div
     * @param bool $initiallyVisible if false, the %HTML content is initially hidden. $title is always visible
     * @param string $divId ID attribute for the div
     * @param string $titleType %HTML to wrap the $title
     * @return string   Generated %HTML string for the toggable div (with $title as heading) 
     */
    public static function toggleDiv($title, $content, $initiallyVisible = false, $divId = "", $titleType = "h4") {
        $display = ($initiallyVisible) ? ("block") : ("none");
        $divId = ($divId) ? ($divId) : ("tdiv-" . rand());

        $html = "<$titleType title = 'Click to expand' onclick = \"$('.toggledDivs').hide(); $('#$divId').toggle();\" style = 'cursor:pointer; color:#817339;'>$title</$titleType>";
        $html .= "<div style = 'display:$display;' class = 'toggledDivs' id = '$divId'>";
        $html .= "$content</div><br />";
        return $html;
    }

    /**
     * When echoing object of this class, generate the %HTML output!
     */
    public function __toString() {
        $html = $this->getHead();

        $html .= '<body>' . PHP_EOL;
        $html .= $this->body . PHP_EOL;
        $html .= '</body>' . PHP_EOL;
        $html .= '</html>' . PHP_EOL;

        return $html;
    }

    /**
     * returns just the <head></head> section.
     */
    public function getHead() {
        $html = '<html>' . PHP_EOL;
        $html .= '<head>' . PHP_EOL;
        $html .= '<title>' . $this->title . '</title>' . PHP_EOL;
        $html .= $this->head . PHP_EOL;
        $html .= '</head>' . PHP_EOL;

        return $html;
    }

    /**
     * Prints <head></head> section, and opens the <body> tag.
     */
    public function startBody() {
        echo $this->getHead() . PHP_EOL;
        echo '<body>' . PHP_EOL;
    }

    /**
     * Exits, closing the </body> and rest of the tags!
     */
    public function done() {
        echo '</body></html>';
        exit();
    }

}

?>