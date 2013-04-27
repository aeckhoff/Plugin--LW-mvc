<?php

namespace LWmvc\Model;

class validationErrorsException extends \Exception
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
    
}
