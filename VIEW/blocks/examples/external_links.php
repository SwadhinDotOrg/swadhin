<?php

/**
 * \brief project specific class: "%HTML Block" example.
 * 
 * @author Shafiul Azam
 * A demonstration-purpose class for presenting how to write a class to create blocks.
 */

class ExamplesExternal_links{
    /** @var Blocks */
    private $block;         ///< An instance of the custom class "Blocks" - created in the constructor for your convenience.
    
    /**
     * Instantiate $block and build the block
     */
    
    public function __construct() {
        $this->block = new Blocks("Help Links");
        // Construct the Block! First construct links...
        $linksArr = array(
            Html::anchor('http://swadhindotorg.github.com/swadhin', 'Code Documentation'),
            Html::anchor('http://swadhin.sf.net', "Project Homepage")
        );
        $this->block->items = $linksArr;
    }
    
    /**
     * Returns the generated html
     * @return string | generated html 
     */
    
    public function get(){
        return $this->block->create();
    }
}
