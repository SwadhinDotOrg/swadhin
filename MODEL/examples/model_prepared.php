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
 * \brief Demonstrates using prepared statements.
 * 
 * Your class must extend CoreModel
 * 
 * @author Shafiul Azam
 */
class ExamplesModel_prepared extends CoreModel {

    /**
     * Constructor must call parent's constructor
     */
    public function __construct($core) {
        // Must call parent's constructor
        parent::__construct($core);
        // Should set name of the table!
        $this->db->table = "user";
    }

    function dummy() {
        // I do nothing
    }

    /**
     * Demo function to perform a registration
     * @return  
     */
    public function select($identifier=null, $returnPointer=true) {
        $this->db->returnPointer = $returnPointer;
        $this->db->identifier = $identifier;
    }

    public function selectAll() {
        $this->db->clear();
        $this->db->prepareSelect();
        if (!$this->db->execute())
            throw new Exception('Failed to execute prepared statement!');
        // Return data as HTML
//        $html = Html::tr(array(), 'th');
        $html = '';
        $this->db->fetchStyle = PDO::FETCH_NUM;
        while ($row = $this->db->fetch()) {
            $html .= Html::tr($row);
        }
        return $html;
    }

    /**
     * Insert 2 rows generating dummy data. \n
     * For example, we're inserting two rows only. In practice, you can do as many as you want!
     */
    public function randomInsert() {
        $this->db->clear();
        $this->db->data = array('username', 'password', 'email', 'date');
        $this->db->prepareInsert();
        // Insert New Data. 1st argument to $this->db->execute() is the data array.
        if (!$this->db->execute(array('user' . rand(0, 500), sha1(rand(0, 99)), 'testingPDO@phpizza.c', time())))
            throw new Exception('Failed to execute prepared statement (in phase 1)!');
        // Insert another
        if (!$this->db->execute(array('user' . rand(0, 500), sha1(rand(0, 99)), 'testingPDO@phpizza.com', time())))
            throw new Exception('Failed to execute prepared statement (in phase 2)!');
    }

    public function updateDate() {
        $this->db->clear();
        $this->db->data = array('date'); // Only update date column 
        $this->db->identifier = array('email'); // only 'email' column will go to WHERE part of query
        $this->db->prepareUpdate();
        if (!$this->db->execute(array(time(), 'testingPDO@phpizza.c')))
            throw new Exception ('Failed to update!');
    }

}
