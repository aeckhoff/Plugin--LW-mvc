<?php

namespace LWmvc\Model;

class EntityCollectionFactory
{
    public static function buildCollectionFromQueryResult($Objectname, $dtos)
    {
        foreach($dtos as $dto) {
            $entities[] = \LWmvc\Model\EntityFactory::buildEntityFromDTO($Objectname, $dto);
        }
        return new \LWmvc\Model\EntityCollection($entities);        
    } 
}