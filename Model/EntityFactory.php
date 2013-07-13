<?php

namespace LWmvc\Model;

class EntityFactory
{

    public static function buildEntity($Objectname, $dto, $id=false)
    {
        if (class_exists($Objectname)) {
            $entity = new $Objectname();
        }
        else {
            $entity = new \LWmvc\Model\Entity();
        }
        $entity->setDTO($dto);
        $entity->setId($id);
        return $entity;
    }
    
    public static function buildEntityFromDTO($Objectname, $dto, $id=false)
    {
        $entity = \LWmvc\Model\EntityFactory::buildEntity($Objectname, $dto, $id);
        $entity->setLoaded();
        $entity->setDirty();
        return $entity;
    }
    
}
