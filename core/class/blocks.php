<?php

/**
 * \brief Generate %HTML blocks.
 * 
 * Use this function to generate %HTML blocks - mostly to use in your Template 's sidebars.
 * 
 * \note To override this class, simply copy this file to custom/class/ directory and make any 
 * changes you want.
 * 
 * This class is helpful to generate common-style %HTML. Call $this->create() function to get generated %HTML. 
 * Generated %HTML contains:
 *  - A title for the block
 *  - Arbitrary (optional) html
 *  - %HTML list
 *  - Arbitrary (optional) html 
 * 
 */

class Blocks extends Html {

    public $title = "";     ///< Title for the block
    public $items = array();    ///< 1-dimension array for list items.  
    public $postHTML = "";  ///< html that should be printed after the list
    public $preHTML = "";   ///< html that should be printed before the list
    public $headTag = "h2"; ///<    html tag that should surround the $title
    
    
    /**
     * Simple Constructor
     * @param string $title Title for the block. 
     */

    public function __construct($title = "") {
        $this->title = $title;
    }

    /**
     * Generates output %HTML - in this function you must return the desired %HTML for the block. 
     * @return string generated %HTML 
     */
    
    public function create() {
        $hTag = $this->headTag;
        $this->html = "<div>
            <$hTag>" . $this->title . "</$hTag>
                ";

        $this->html .= $this->preHTML . "<ul>";
        if (isset($this->items)){
//            foreach ($this->items as $k => $v){
//                $attr = (isset($this->attrs[$k]))?($this->attrs[$k]):('');
//                $this->html .= "<li><a href = '$v'" . $attr . " >$k</a></li>\n";
//            }
            $this->html .= $this->li($this->items);
        }
        $this->html .=  '</ul>';
        $this->html .= $this->postHTML . '</div>';
        return $this->html;
    }

}

?>
