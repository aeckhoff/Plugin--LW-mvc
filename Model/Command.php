<?php

namespace LWmvc\Model;

class Command
{
    
    private $commandName;
    private $response;
    private $parameter = array();
    private $data = array();
    private $history = array();
    private $modelName; 
    
    public function __construct($modelName, $commandName)
    {
        $this->modelName = $modelName;
        $this->commandName = $commandName;
        $this->response = \LWmvc\Model\Response::getInstance();
    }

    public static function getInstance($modelName, $commandName)
    {
        return new Command($modelName, $commandName);
    }
    
    public function getCommandName()
    {
        return $this->commandName;
    }
    
    public function getModelName()
    {
        return $this->modelName;
    }
    
    public function setParameterByKey($key, $value)
    {
        $this->parameter[$key] = $value;
        return $this;
    }
    
    public function getParameterByKey($key)
    {
        return $this->parameter[$key];
    }
    
    public function setDataByKey($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }
    
    public function getDataByKey($key)
    {
        return $this->data[$key];
    }
    
    public function getDataArray()
    {
        return $this->data;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function addCommandHistory($message, $modifyingCommand=false)
    {
        $history['message'] = $message;
        $history['modifyingCommand'] = $modifyingCommand;
        $this->history[] = $history;
        return $this;
    }

    public function getCommandHistory()
    {
        return $this->history;
    }

    public function getModifyingCommandHistory()
    {
        foreach($this->history as $command) {
            if ($command['modifyingCommand'] === true) {
                $array[] = $command;
            }
        }
        return $array;
    }
}
