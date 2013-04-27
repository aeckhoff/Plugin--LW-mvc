<?php

namespace LWmvc\Model;

class CommandResolver
{

    protected $dic;
    protected $command;
    protected $baseNamespace;
    
    public function __construct($command)
    {
        $this->command = $command;
    }

    public function setBaseNamespace($baseNamespace)
    {
        $this->baseNamespace = $baseNamespace;
    }
    
    public function setObjectClass($objectClass)
    {
        $this->ObjectClass = $objectClass;
    }
    
    protected function getCommandHandler()
    {
        if (!$this->commandHandler) {
            $class = $this->baseNamespace.'DataHandler\CommandHandler';
            $this->commandHandler = new $class(\lw_registry::getInstance()->getEntry("db"));
        }
        return $this->commandHandler;
    }
    
    protected function getQueryHandler()
    {
        if (!$this->queryHandler) {
            $class = $this->baseNamespace.'DataHandler\QueryHandler';
            $this->queryHandler = new $class(\lw_registry::getInstance()->getEntry("db"));
        }
        return $this->queryHandler;
    }
    
    protected function postSaveWork($result, $id, $entity)
    {
        if ($result) {
            $entity->setLoaded();
            $entity->unsetDirty();
        }
        else {
            if ($id > 0 ) {
                $entity->setLoaded();
            }
            else {
                $entity->unsetLoaded();
            }
            $entity->setDirty();
            throw new \Exception('An DB Error occured saving the Entity');
        }        
    }     
}
