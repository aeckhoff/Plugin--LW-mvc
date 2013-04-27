<?php

namespace LWmvc\Model;

class FilterFactory
{
    public static function buildFilter($baseNamespace)
    {
        $class = $baseNamespace.'Service\Filter';
        if (class_exists($class)) {
            return new $class();
        }
        else {
            return new \LWmvc\Model\Filter();
        }
    }
}