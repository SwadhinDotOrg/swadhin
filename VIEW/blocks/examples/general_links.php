<?php

/* * ***** ****** ****** ****** ****** ******
 *
 * Author       :   Shafiul Azam
 *              :   ishafiul@gmail.com
 *              :   Project Manager, 
 *              :   PROGmaatic Developer Network
 * Page         :
 * Description  :   
 * Last Updated :
 *
 * ****** ****** ****** ****** ****** ***** */

/**
 * \brief project specific class: "%HTML Block" example.
 * 
 * @author Shafiul Azam
 * A demonstration-purpose class for presenting how to write a class to create blocks.
 */

class ExamplesGeneral_links{
    
    private $block; ///< ///< An instance of the custom class "Blocks" - created in the constructor for your convenience.
    
    /**
     * Instantiate $block and build the block
     */
    
    public function __construct() {
        $this->block = new Blocks("Examples");
        // Construct the Block! First construct links...
        // Static links (nested)
        $staticPages = $this->block->li(array(
            anchor_static("examples/sample/demo2", "examples/sample/demo2"),
            anchor_static("examples/demo1", "examples/demo1")
        ));
        $generalPages = $this->block->li(array(
            anchor("index", "home"),
            anchor("examples/sample/demo1", "examples/sample/demo1"),
            anchor("examples/simple_validator","Simple Validator"),
            anchor("examples/many_views","Dynamic views")
        ));
        $dbTests = $this->block->li(array(
            anchor('examples/plainclean', 'Templates'),
            anchor('examples/pdo_demo', 'DB Prepared test')
        ));
        $formPages = $this->block->li(array(
            anchor("examples/registration", "Registration"),
            anchor("examples/login", "login"),
        ));
        // Finally, set the items
        $this->block->items = array(
            "General pages $generalPages",
            "Static Pages $staticPages",
            "Forms $formPages",
            'Interesting' . $dbTests
        );
    }
    
    /**
     * Returns the generated html
     * @return string | generated html 
     */
    
    public function get(){
        return $this->block->create();
    }
}
