<?php

class View extends Template{
    function __construct($core) {
        parent::__construct($core);
    }
    
    function mainContent() {
        
        echo '<h3>Prepared Statements demonstration using PDO (mysql implementation)</h3>';
        echo '<br />';
        echo Html::li(array(
            anchor('examples/pdo_demo/select', 'Select Operation'),
            anchor('examples/pdo_demo/insert', 'Insert Operation'),
            anchor('examples/pdo_demo/update', 'Update Operation'),
            anchor('examples/pdo_demo/delete', 'Delete Operation'),
        ));
        echo '<br /><hr /><br />';
        echo '<h3>Database State</h3> <br />';
        echo '<table border="1">';
        echo $this->all;
        echo '</table>';
    }
}
