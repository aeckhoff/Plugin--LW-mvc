<?php

namespace LWmvc;

class View
{
    public function __construct($type=false)
    {
        $this->view->type = $type;
    }

    public function setDic($dic)
    {
        $this->dic = $dic;
    }
    
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
    
    public function setErrors($errors=false)
    {
        $this->view->errors = $errors;
    }
    
    public function setAggregate($aggregate)
    {
        $this->view->aggregate = $aggregate;
    }
    
    public function setIsDeletableSpecification($isDeletable)
    {
        $this->view->deletableSpecification = $isDeletable;
    }    

    public function baseCrudFormRender($backCommand, $addCommand="add", $saveCommand="save", $deleteCommand=false)
    {
        if ($this->view->type == "add") {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>$addCommand));
        }
        else {
            $this->view->actionUrl = \lw_page::getInstance()->getUrl(array("cmd"=>$saveCommand, "id" => $this->entity->getId()));
        }
        $this->entity->renderView($this->view);
        $this->view->backUrl = \lw_page::getInstance()->getUrl(array("cmd"=>$backCommand));
        return $this->view->render();
    }
}