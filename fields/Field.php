<?php
namespace Mpm\Forms\Fields;
use Mpm\Validation\Validator;

define("FIELD_CONTAINER_CLASS","form-field");
define("FIELD_CONTAINER_ID_PREFIX","id-form-field-");
define("FIELD_ID_PREFIX","id-");
define("FIELD_LABEL_ID_PREFIX","id-label-");
define("FIELD_CLASS","form-control");
define("FIELD_LABEL_CLASS","form-label");
define("RADIOGROUP_CLASS","form-check");
define("RADIOFIELD_CLASS","form-check-input");
define("RADIOFIELD_LABEL_CLASS","form-check-label");
define("RADIOGROUP_ID_PREFIX","id-radio-group-");
define("SELECTGROUP_CLASS","form-select");
define("ERROR_PREFIX","error-");
define("ERROR_CLASS","error-list");
define("CHECKFIELD_CLASS","form-check-input");
define("CHECKFIELD_DIV_CLASS","form-check");
define("CHECKFIELD_LABEL_CLASS","form-check-label");

class Field {
  public $name,
         $label,
         $value,
         $is_valid,
         $validation,
         $error_list;
 
  function __construct($label="",$value="",$validation=[],$error_list=[]){
    $this->label = $label;
    $this->value = $value;
    $this->validation = $validation;
    $this->error_list= $error_list;
  }
  
  /** 
   * Generate Id for form field by merging prefix and suffix.
   * 
   * @param string $prefix eg. id-
   * @param string $suffix eg. this is name of the field.
   * @return string $prefix.$suffix
   */
  protected function generateId($prefix,$suffix){
    $suffix = strtolower(trim($suffix));
    $suffix = str_replace(" ","-",$suffix);
    $id = $prefix . $suffix;
    return $id;
  }
  
  /**
   * Sets name instance variable . 
   * In forms this the value of name attribute
   * 
   * @param string $name
   */
  public function setName($name){
    $this->name = $name;
  }
  
  /**
   * returns name 
   */
  public function getName(){
    return $this->name;
  }
  
  /** 
   * Sets label for field 
   * 
   * @param string $label 
   */
  public function setLabel($label){
    $this->label = empty($this->label)?ucfirst($label):$this->label;
  }
  
  /**
   * Gets label of field
   * 
   */
  public function getLabel(){
    return $this->label;
  }
  
  /** 
   * Sets value instance variable .
   * In forms this  value is value-attribute of the field . 
   * 
   * @param $value
   */
  public function setValue($value){
    $this->value = $value;
  }
  
  /**
   * Sets Error list 
   * 
   * @param array $error_list 
   */
  public function setErrorList($error_list){
    $this->error_list = $error_list;
  }
  
  /**
   * Gets Error list 
   * 
   * @return array
   */
  public function getErrors(){
    return $this->error_list;
  }
  
 /**
  * Handles Specific Field validations
  */
  public function validate(){
    $response = Validator::validate_value($this->name,$this->value,$this->validation);
    $this->is_valid   = $response['valid'];
    $this->error_list = $response['error_list'];
  }
  
  /**
   * Returns Sanitized value of the field
   */
  public function clean(){
    
  }
  
  /**
   * Alias of create_field method 
   */
  public function create() {
    return $this->create_field();
  }
  /**
   * Returns Default Input Field html code
   */
  public function create_field(){
    $FIELD_CONTAINER_CLASS = FIELD_CONTAINER_CLASS;
    $FIELD_LABEL_CLASS = FIELD_LABEL_CLASS;
    $FIELD_CLASS       = FIELD_CLASS;
    $fieldContainerId  = $this->generateId(FIELD_CONTAINER_ID_PREFIX,$this->name);
    $labelID           = $this->generateId(FIELD_LABEL_ID_PREFIX,$this->name);
    $id                = $this->generateId(FIELD_ID_PREFIX,$this->name);
    $errors = array_map(fn($error)=>"<li>{$error}</li>",$this->error_list);
    $errors = join(' ',$errors);
    return "
    <div class='{$FIELD_CONTAINER_CLASS}' id='{$fieldContainerId}'>
      <label class='{$FIELD_LABEL_CLASS}' id='{$labelID}'>{$this->label}</label>
      <input name='{$this->name}' type='text' class='{$FIELD_CLASS}' id='{$id}' value='{$this->value}'/>
      <ul class='errors'>
          {$errors}
      </ul>
    </div>
    ";
  }
}