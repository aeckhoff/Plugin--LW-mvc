<?php

namespace LWmvc\Model;

class Response
{
    private $parameter;
    private $output;
    private $data;
    
    public function __construct()
    {
    }
    
    public static function getInstance()
    {
        return new Response();
    }    
    
    public function setParameterByKey($key, $value)
    {
        $this->parameter[$key] = $value;
    }
    
    public function getParameterByKey($key)
    {
        return $this->parameter[$key];
    }    
    
    public function getParameterArray()
    {
        return $this->parameter;
    }    
    
    public function setDataByKey($key, $value)
    {
        $this->data[$key] = $value;
    }
    
    public function getDataByKey($key)
    {
        return $this->data[$key];
    }
    
    public function getDataArray()
    {
        return $this->data;
    }
    
    public function setOutputByKey($key, $output)
    {
        $this->output[$key] = $output;
    }
    
    public function getOutputByKey($key)
    {
        return $this->output[$key];
    }
}
