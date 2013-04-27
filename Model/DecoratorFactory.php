<?php

namespace LWmvc\Model;

class DecoratorFactory
{
    public static function buildDecorator($baseNamespace)
    {
        $class = $baseNamespace.'Service\Decorator';
        if (class_exists($class)) {
            return new $class();
        }
        else {
            return new \LWmvc\Model\Decorator();
        }
    }
}