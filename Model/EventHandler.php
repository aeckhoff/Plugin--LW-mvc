<?php

namespace LwModel;

class EventHandler 
{
    public function __construct()
    {
        $this->dic = new \lwListtool\Services\dic();
    }
    
    public function execute($event)
    {
        $this->event = $event;
        $method = $this->event->getEventName();
        return $this->$method();
    }
    
    protected function getCommandHandler()
    {
        if (!$this->commandHandler) {
            $class = $this->baseNamespace.'Model\CommandHandler';
            $this->commandHandler = new $class($this->dic->getDbObject());
        }
        return $this->commandHandler;
    }
    
    protected function getQueryHandler()
    {
        if (!$this->queryHandler) {
            $class = $this->baseNamespace.'Model\QueryHandler';
            $this->queryHandler = new $class($this->dic->getDbObject());
        }
        return $this->queryHandler;
    }
 
    protected function getDataValueObjectFilter()
    {
        if (!$this->DataValueObjectFilter) {
            $class = $this->baseNamespace.'Service\Filter';
            $this->DataValueObjectFilter = new $class();
        }
        return $this->DataValueObjectFilter;
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
            throw new Exception('An DB Error occured saving the Entity');
        }        
    }
    
    public function getIsDeletableSpecification()
    {
        $class = $this->baseNamespace.'Specification\isDeletable';
        $this->event->getResponse()->setDataByKey('isDeletableSpecification', $class::getInstance());
        return $this->event->getResponse();
    }
    
    public function getIsValidSpecification()
    {
        $class = $this->baseNamespace.'Specification\isValid';
        return $class::getInstance();
    }
    
    public function buildObjectByArray($data)
    {
        $object = $this->buildObjectById($data['id']);
        return $this->prepareObject($object, $data);
    }    
    
    public function buildAggregateFromQueryResult($result)
    {
        foreach($items as $item) {
            $entities[] =  $this->buildObjectByArray($item);
        }
        return new \LWmvc\Model\EntityAggregate($entities);        
    }
    
    public function buildEntityFromArray($array)
    {
        $dataValueObject = new \LWmvc\Model\ValueObject($array);
        return $this->buildEntityFromValueObject($dataValueObject);
    }
    
    public function buildEntityFromValueObject($data)
    {
        $entity = $this->getNewModelObject();
        $entity->setDataValueObject($data);
        $entity->setLoaded();
        $entity->setDirty();
        return $entity;
    }
    
    public function saveEntity($entity)
    {
        if ($entity->getId() > 0 ) {
            $result = $this->getCommandHandler()->saveEntity($entity->getId(), $entity->getValues());
            $id = $entity->getId();
        }
        else {
            $result = $this->getCommandHandler()->addEntity($this->event->getParameterByKey("listId"), $entity->getValues());
            $id = $result;
        }
        $this->postSaveWork($result, $id, $entity);
        return $id;
    }
}
