<?php

namespace LWmvc\Model;

class SpecificationFactory
{
    public static function buildIsDeletableSpecification($baseNamespace)
    {
        $class = $baseNamespace.'Specification\isDeletable';
        return $class::getInstance();
    }
    
    public static function buildIsValidSpecification($baseNamespace)
    {
        $class = $baseNamespace.'Specification\isValid';
        return $class::getInstance();
    }
}