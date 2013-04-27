<?php

namespace LWmvc\Model;

class EntityCollection implements \Iterator
{
    private $entities;
    private $position = 0;
    
    function __construct($entities = false)
    {
        $this->entities = $entities;
        $this->position = 0;
    }

    function count()
    {
        return count($this->entities);
    }
    
    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->entities[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->entities[$this->position]);
    }
    
    function getEntities()
    {
        return $this->entities;
    }
}
