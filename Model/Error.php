<?php

namespace LWmvc\Model;

class Error
{
    private $error;
    private $count;
    
    public function __construct()
    {
        $this->reset();
    }
    
    public static function getInstance()
    {
        return new Error();
    }

    public function reset()
    {
        unset($this->error);
        $this->count = 0;
    }
    
    public function addErrorByKey($key, $message, $errorNumber=false)
    {
        $error['message'] = $message;
        $error['errorNumber'] = $errorNumber;
        $this->error[$key][] = $error;
        $this->count++;
    }
    
    public function getErrorsByKey($key)
    {
        return $this->error[$key];
    }
    
    public function getErrors()
    {
        return $this->error;
    }
    
    public function getErrorCount()
    {
        return $this->count;
    }
    
    public function hasErrors()
    {
        if ($this->count>0) {
            return true;
        }
        return false;
    }
}
