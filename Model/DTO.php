<?php

namespace LWmvc\Model;

class DTO
{
    private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function getValueByKey($name)
    {
        return $this->data[$name];
    }
    
    public function getValues()
    {
        return $this->data;
    }
}