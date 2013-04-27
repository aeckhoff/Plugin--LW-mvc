<?php

namespace LWmvc\Model;

define("LWMVC_EMAIL_ERROR", "lwmvc_1");
define("LWMVC_MAXLENGTH_ERROR", "lwmvc_2");
define("LWMVC_MINLENGTH_ERROR", "lwmvc_3");
define("LWMVC_REQUIRED_ERROR", "lwmvc_4");
define("LWMVC_ALNUM_ERROR", "lwmvc_5");
define("LWMVC_BETWEEN_ERROR", "lwmvc_6");
define("LWMVC_DIGITS_ERROR", "lwmvc_7");
define("LWMVC_GREATERTHAN_ERROR", "lwmvc_8");
define("LWMVC_LESSTHAN_ERROR", "lwmvc_9");
define("LWMVC_INTEGER_ERROR", "lwmvc_10");
define("LWMVC_FILETYPE_ERROR", "lwmvc_11");
define("LWMVC_IMAGE_ERROR", "lwmvc_12");
define("LWMVC_LWDATE_ERROR", "lwmvc_13");
define("LWMVC_UNDEFINED_ERROR", "lwmvc_14");
define("LWMVC_BOOL_ERROR", "lwmvc_15");
define("LWMVC_LWDATESEQUENCE_ERROR", "lwmvc_16");

class Validator
{
    public function __construct()
    {       
        
    }
    
    public function isSatisfiedBy($entity)
    {
        $this->setDataArray($entity->getValues());
        $this->resetErrors();
        
        $valid = true;
        foreach($this->allowedKeys as $key){
            $method = $key."Validate";
            if (method_exists($this, $method)) {
                $result = $this->$method($this->array[$key]);
                if($result == false){
                    $valid = false;
                }
            }
        }
        return $valid;
    }    
    
    protected function setDataArray($array)
    {
        $this->array = $array;
    }
    
    protected function resetErrors()
    {
        unset($this->errors);
        $this->errors = array();
    }
    
    protected function addError($key, $number, $array=false)
    {
        $this->errors[$key][$number]['error'] = 1;
        $this->errors[$key][$number]['options'] = $array;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getErrorsByKey($key)
    {
        return $this->errors[$key];
    }
    
    public function isLwDate($value)
    {
        if (strlen($value) != 8) {
            return false;
        }
        $year = substr($value, 0, 4);
        $month = substr($value, 4, 2);
        $day = substr($value, 6, 2);

        if ($year<1000) {
            return false;
        }
        if ($month > 12 || $month < 1) {
            return false;
        }
        if ($day > 31 || $day < 1) {
            return false;
        }
        return true;
    }
    
    public function isEmail($value)
    {
        if(filter_var($value, FILTER_VALIDATE_EMAIL) == false) {
            return false;
        }
        return true;
    }    
    
    static function hasMaxlength($value, $options) 
    {
        if (strlen(trim($value)) > intval($options['maxlength'])) {
            return false;
        }
        return true;
    }
    
    static function hasMinlength($value, $options) 
    {
        if (strlen(trim($value)) < intval($options['minlength'])) {
            return false;
        }
        return true;
    }
    
    static function isRequired($value) 
    {
        if (strlen(trim($value)) < 1) {
            return false;
        }
        return true;
    }    
    
    static function isAlnum($value) 
    {
        $test = preg_replace('/[^a-zA-Z0-9\s]/', '', (string) $value);
        if ($this->isRequired($value) && ($value != $test)) {
            return false;
        }
        return true;
    }
    
    static function isBetween($value, $options) 
    {
        if ($this->isRequired($value) && ($value < strval($options["value1"]) || $value > strval($options["value2"]))) {
            return false;
        }
        return true;
    }    
    
    static function isDigits($value) 
    {
    	$test = preg_replace('/[^0-9]/', '', (string) $value);
        if ($this->isRequired($value) && ($value != $test)) {
            return false;
        }
        return true;
    }
    
    static function isGreaterThan($value, $options) 
    {
        if ($this->isRequired($value) && ($value < strval($options["value"]))) {
            return false;
        }
        return true;
    }

    static function isLessThan($value, $options) 
    {
        if ($this->isRequired($value) && ($value > strval($options["value"]))) {
            return false;
        }
        return true;
    }
    
    static function isInt($value) 
    {
        if ($value && (!is_numeric($value))) {
            return false;
        }
        return true;
    }
    
    static function isFiletype($value, $options) 
    {
    	if ($this->isRequired($value)) {
            $ext = strtolower(substr($value,strrpos($value,'.')+1,strlen($value)));
            if (!strstr($options["extensions"], ":".$ext.":")) {
                return false;
            }
        }
        return true;
    }

    static function isImage($value) 
    {
        return $this->isFiletype($value, array('value',':jpg:jpeg:png:gif:'));
    }
}