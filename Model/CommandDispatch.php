<?php

namespace LWmvc\Model;

class CommandDispatch
{
    public function __construct()
    {
    }

    public static function getInstance()
    {
        return new CommandDispatch();
    }
    
    public function execute($package, $model, $commandName, $parameterArray=array(), $dataArray=array())
    {
        $command = \LWmvc\Model\Command::getInstance($model, $commandName);
        foreach($parameterArray as $key => $value) {
            $command->setParameterByKey($key, $value);
        }
        foreach($dataArray as $key => $value) {
            $command->setDataByKey($key, $value);
        }
        if (substr($command->getCommandName(), 0, 9) == "lwCommand") {
            $class = "\\LWmvc\Model\\CommandResolver\\".$command->getCommandName();
            $commandResolver = $class::getInstance($command);
            $commandResolver->setBaseNamespace("\\".$package."\\Model\\".$command->getModelName()."\\");
            $commandResolver->setObjectClass("\\".$package."\\Model\\".$command->getModelName()."\\Object\\".$command->getModelName());
            return $commandResolver->resolve();
        }
        else {
            $class = "\\".$package."\\Model\\".$command->getModelName()."\\CommandResolver\\".$command->getCommandName();
            return $class::getInstance($command)->resolve();
        }        
        $class = "\\".$package."\\Model\\".$model."\\CommandResolver\\".$commandName;
        return $class::getInstance($command)->resolve();        
    }
}
