<?php

// Constants

/**
 * \brief Create, submit & validate web-forms easily! Your Forms should extend this class.
 * 
 * \see Complete Tutorial: http://pizzamvc.com/phpizza?page=29
 * 
 * This class provides opportunities for:
 * - Creating web forms easily, withtout writing any %HTML: within your %Controller
 * - Send object of a form to %View class
 * - Process submission of a form, & perform validation
 * - If validation fails, automatically generate Error messages & re-present form to user, keeping user provided data intact.
 */

abstract class CoreForm{
    
    // Constants for internal use
    const DISPLAYNAME = 0;  
    const VALIDATORS = 1;
    const HTML = 2;
    const HTML_FUNCNAME = 3;
    const HTML_FUNC_ARGS = 4;
    
    // Member Variables

    public $action = '';    ///<    Form attribute "action"
    public $method = 'post';    ///<    Form attribute "method"
    public $target = '';        ///<    Form attribute "target"
    public $onSubmit = '';  ///<    Form attribute "onsubmit"
    
    private $elements = array(); ///<    Key-value array for storing form elements
    
    public $submitButtonText = '';  ///<    Text displayed at <b>submit button</b>
    public $tableBorder = '0';  ///<    Table attribute "border"
    public $tableCellSpacing = '';  ///<    Table attribute "cellspacing"
    public $tableCellPadding = '';  ///<    Table attribute "cellpadding"
    
    public $arbritaryHTML = ''; ///<    This %HTML string is printed after form elements
    
    public $fileUpload = false; ///<    Set true if you're uploading file(s) in this form
    
    public $id = "";    ///<    Form attribute "id"
    public $submitButtonId = "";    ///<    Form's submit button's attribute "id"
    public $class = ""; ///<    Form attribute "class"
    
    public $displaySubmissionErrors = true; ///< Set to true if you want to display error messages when form validation fails
    
    public $currentElementName = "";    ///< "name" attribute of currently processing form element - available while validating form
    public $validators = null;      ///< Key-value array, key is "name" attribute or an alement, value is List of validators.
    public $noErrorFormatting = false;
    public $errorStringSeperator = '<br />';
    public $arrVal = null;   ///< getting value from a key-value array safely 
    public $allowGetRequests = false;
    
    private $validate = false;  ///<    Automatically set to true when you're validating a submitted form
    private $submittedData = array();   ///< Used to store user-submitted data by this form
    private $error = "";    ///< Contains error-strings if form validation fails.
    private $formHtml = ""; ///< Contains the %HTML string generated for this form
    private $core = null;   ///<    A reference to the global $core
    private $formName = ""; ///<    Name of the class which extended me ( CoreClass )
    private $isSubmissionValid = null;  ///< Indicator whether form submission validated
    
    // Public & private Methods
    
    /**
     * Constructor
     * 
     * @param object $ob should be always "$this" - reference to the child class.
     * @param Core $core a reference to the global $core 
     */
    
    public function __construct($ob,$core) {
        $this->core = $core;
        $this->formName = get_class($ob);
        // Also, send my reference to the validator
        $this->core->validate->form = $this;
        // Attrributes
        $this->submitButtonText = 'Submit';
    }
    
    /**
     * Creates & returns %HTML for this form. 
     * - You do not need to use this function at all if you use sendToView() function. Example: CONTROL/registration.php & VIEW/registration.php
     * - If you want to manually send the generated %HTML of the form to your view class, you may call this function within your controller. See example: CONTROL/login.php & VIEW/login.php
     * 
     * @return none
     */
    
    private function generateHTML() {
        // Creates the HTML form and returns the HTML
        // Output HTML 
        $fileUploadCode = ($this->fileUpload)?("enctype='multipart/form-data'"):("");
        $this->formHtml = '<form class="html-form ' . $this->class . '" ' . $fileUploadCode . ' method = "' . $this->method . '" action = "' . LibUrl::url($this->action) . '" target = "' . $this->target . '" onsubmit = "' . $this->onSubmit . '" id = "' . $this->id . '">';
        $this->formHtml .= '<table class="html-form-table" cellspacing = "' . $this->tableCellSpacing . '" cellpadding =  "' . $this->tableCellPadding . '"  border = "' . $this->tableBorder . '"><tbody>';
        // Loop through the components and print one component per row
//        echo count($this->elements);
        foreach($this->elements as $elemName=>$content){
            // Get user-submitted, validated data
//            echo '<br />For element: ' . $elemName . '<br />';
            $argArr = $this->elements[$elemName][self::HTML_FUNC_ARGS];
            $argArr[2] = $this->submittedData[$elemName];
            $elemHTML = call_user_func_array(array("HTML",$content[self::HTML_FUNCNAME]), $argArr);
            $this->formHtml .= Html::tr(array($content[CoreForm::DISPLAYNAME],$elemHTML)) . "\n";
        }
        $this->formHtml .= '</tbody></table><br />' . $this->arbritaryHTML . '<br />';
        $this->formHtml .= '<input id="'. $this->submitButtonId .'" class=html-form-submit type = "submit" value = "' . $this->submitButtonText . '" />';
        $this->formHtml .= '</form>';
    }
    
    /**
     * \brief Generates the form's %HTML & passes it to View classes. 
     * 
     * Call this function in your Controller classes to automatically send the generated %HTML of the form to your View class.
     * 
     * Later, in your view class, you can call CoreView::form($id) to get the generated html, where $id is the class name of the form
     * 
     * @param $returnOnly bool
     *  - is false (default) does not return the generated %HTML. The %HTML is available in your View classes.
     *  - if true, <b>returns</b> the generated %HTML. Also does <b>not</b> pass validation errors as <i>status messages</i>
     */
    
    public function sendToView($returnOnly = false){
        if($this->error && !$returnOnly)
            $this->core->funcs->setStatusMsg($this->error);
        
        // Set display names
        if(!$this->validate){
            foreach ($this->validators as $elemName=>$temp){
                $this->elements[$elemName][CoreForm::DISPLAYNAME] = $temp[0];
            }
        }
            
        $this->createElements();
        $this->generateHTML();
        if($returnOnly)
            return $this->formHtml;
        $this->core->formData[$this->formName] = $this->formHtml;
    }
    
    /**
     * \brief Used to validate the form after user submits it.
     * 
     * Important Function  - After a user has submitted the form, call it in your controller to start form validation process
     * 
     * Validation functions are applied one after another on each element of the form.
     *
     * @return bool - true if all validation tests were successful. False otherwise.
     */ 
    
    public function validate(){
        $this->validate = true;
        $this->error = array();
        // Run validation
        foreach ($this->validators as $elemName=>$temp){
            $this->elements[$elemName][CoreForm::DISPLAYNAME] = $temp[0];
            if(isset ($temp[1]) && !empty ($temp[1])){
                $this->submittedData[$elemName] = $this->doValidation($elemName,$temp[1]);
            }else{
                $this->submittedData[$elemName] = isset ($_POST[$elemName])?($_POST[$elemName]):("");
            }
        }
        $this->error = implode($this->errorStringSeperator, $this->error);
        $this->isSubmissionValid = (empty($this->error))?(true):(false);
        return $this->isSubmissionValid;
    }
    
    // Element related
    
    /**
     * \brief Returns valid, user-submitted data of a form element.
     * 
     * Call this function within your constructor to get valid, user-submitted value for a single element.
     * @param string $name -  the "name" attribute of the element
     * @return string | validated user submitted data 
     */
    
    public function get($name){
        return (isset($this->submittedData[$name]))?($this->submittedData[$name]):(null);
    }
    
    public function arrVal($index){
        if(isset($this->arrVal[$index]))
            return $this->arrVal[$index];
        else
            return "";
    }
    
    /**
     * Returns errors (if any) created while validating the form.
     * 
     * @return string | Error string. Generated only if form submission failed.
     */
    
    public function getError(){
        return $this->error;
    }


    /**
     * Call this function passing your form elements as argument.
     * 
     * @param array $elem is a mixed array. Each element of the form will contribute an element to this array. 
     * 
     * <b>Optional Reading: You can avoid reading following section if you're not interested how the framework works!</b>
     * 
     * For each element, we need an array (key-value) of following format:
     *  - key is: string, "name" attribute of the form element.
     *  - value is: array (mixed) of following type:
     *      - 0th element: string | Label (user-friendly display name) of the element
     *      - 1st element: string | names of validation functions seperated by "|" - see documentations for details. 
     *          - seperate each function name by a "|" character (without the quotes)
     *          - you can pass parameters to a function. To pass parameter: type the parameters after the function name, seperated by commas ","
     *          - if you are going to pass a paramter which itself contains comma "," character(s), escape each comma by a slash "\"
     *              -   For example: "required|limit,3,5|email" means:
     *              -   First, CoreValidator::required() will be called on the subject
     *              -   Next, CoreValidator::limit() will be called with parameters "3" (1st parameter) & "5" (2nd parameter)
     *              -   Finally, CoreValidator::email() will be called on the subject
     *      - 2nd element: name of the function that will be called to generate %HTML for this element.
     *      - 3rd element: an array whose elements will be passed to the function just mentioned (2nd element) as parameters. 
     * 
     * 
     * Validation functions are located in CoreValidator (in core/class directory) & Validator (in custom/class directory) classes. 
     * You can define your own validation functions in Validator class.
     * 
     * You can get complete examples in Login & Registration class (see source code: Login::createElements() Registration::createElements() ). Also, see tutorials.
     */
    
    public function setElements($elem){
        foreach ($elem as $name=>$eArr){
            $arraySuffix = (strpos($name, "[]"))?("[]"):("");
            $name = str_replace("[]", "", $name);
            $this->elements[$name][self::HTML_FUNCNAME] = $eArr[0];
            // arguments of HTML generating funcs
            if(isset ($eArr[1])){
                array_unshift($eArr[1], $name . $arraySuffix); //  push $name at the beginning of the array
            }else{
                $eArr[1] = array($name . $arraySuffix,null);    //  create a new array with only element $name in it
            }
            $this->elements[$name][self::HTML_FUNC_ARGS] = $eArr[1];
            // Store developer provided value. This is available from $eArr[1][2]
            if(empty ($this->submittedData[$name])){
                $this->submittedData[$name] = (empty($eArr[1][2]))?(""):($eArr[1][2]);
            }
        }
    }
    


    /**
     * \brief Returns all user-submitted <b>valid</b> data in a key-value array.
     * 
     * Call this function within your constructor to get VALIDATED user-submitted value of <b>all</b> form elements.
     * @return array | key-value array where key is the "name" attribute of the element & value is the user-submitted valid data.
     */
    
    public function getAll(){
        return $this->submittedData;
    }
    
    /**
     * Returns the Human-readable name (Display Name) of an element. This display name 
     * was set by the 2nd parameter of element() function
     * @param string $name "name" attribute of the element
     * @return string Display-name of the element. 
     */
    
    public function getDisplayName($name){
        return $this->elements[$name][CoreForm::DISPLAYNAME];
    }
    

    /**
     * @name Functions for Internal Use
     * These functions are called automatically within the framework.
     * You should NEVER call these functions in your code!
     */
    
    
    //@{
    
    /**
     * Used Internally to start validation of a form element What it does is:
     *  - Based on the 3rd parameter "$validators" in element() function, seperates all functions to call 
     * on the user provided input for this element. functions () are seperated by the character "|"
     * @param string $name name of the element to start validation
     * @return string | validated user input for this element 
     */
    
    private function doValidation($name,$validators){
        // Strip off array symbols from the name
//        $name = str_replace("[]", "", $name);
        $this->currentElementName = $name;
        $funcsToCall = explode("|", $validators);
        // Get the post value to begin examination!
        if($this->allowGetRequests)
            $this->core->validate->subject = (isset($_REQUEST[$name]))?($_REQUEST[$name]):("");
        else
            $this->core->validate->subject = (isset($_POST[$name]))?($_POST[$name]):("");
        foreach($funcsToCall as $func){
            // get parameters for the function
            // handle escaped values
            $func = str_replace("\,", ":_:@:", $func);
            $params = explode(",", $func);
            $func = $params[0];
            if(!method_exists($this->core->validate, $func)){
                echo "Error: Validation function $func is not defined for element " . $this->getDisplayName($name);
                exit();
            }
            unset ($params[0]);
            $paramsFinalized = array();
            // Handle escaped values 
            foreach ($params as $i){
                $paramsFinalized[] = str_replace(":_:@:", ",", $i);
            }
            call_user_func_array(array($this->core->validate,$func),$paramsFinalized);
        }
        // Check for errors
        $error = $this->core->validate->exitIfInvalid(false, ", ");
        if(!empty($error)){
            if($this->noErrorFormatting)
                $this->error[] = $this->elements[$name][CoreForm::DISPLAYNAME] . ' - ' . $error;
            else
                $this->error[] = '&quot;' . $this->elements[$name][CoreForm::DISPLAYNAME] . '&quot; ' . $error;

        }
        $this->submittedData[$name] = $this->core->validate->subject;    //  subject is now validated!
        return $this->core->validate->subject;
    }
    
    
    //@}
    

    /**
     * @name Abstract functions: implement these functions in Child class.
     */
    
    //@{
    
    /**
     * \brief Constructs the form
     * 
     * Your Form classes should override (implement) this function. Within this function, build an array for 
     * your form-elements. Then call setElements() function, passing the array as argument of setElements()
     * 
     * \see Example: https://github.com/pizzamvc/phpizza/blob/master/VIEW/forms/Registration.php 
     */
    
    abstract public function createElements();  //  To be implemented
    
    public function createValidators(){
        // To be implemented in forms
    }
    
    //@}
    
    
    /**
     * Functions for creating %HTML Forms
     */
    
    //@{
    
    
    
    
    //@}
}


?>