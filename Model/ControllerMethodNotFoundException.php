<?php

namespace LWmvc\Model;

class ControllerMethodNotFoundException extends \Exception
{
    private $errors;
    
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
    
    public function getErrorMessage()
    {
        return $this->getMessage().' Method doesn\'t exist';
    }
    
}