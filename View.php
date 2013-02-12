<?php

/**************************************************************************
*  Copyright notice
*
*  Copyright 2013 Logic Works GmbH
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*  
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*  limitations under the License.
*  
***************************************************************************/

namespace LWmvc;

class View
{
    
    protected $view;
    protected $entity;
    protected $configuration;
    protected $response;
    
    public function __construct($type=false)
    {
        if (class_exists('\lw_registry', true)) {
            $this->setConfiguration(\lw_registry::getInstance()->getEntry('config'));
            $this->setResponse(\lw_registry::getInstance()->getEntry('response'));
        }
        $this->view->type = $type;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function setConfiguration($config)
    {
        $this->configuration = $config;
    }
    
    public function getConfiguration($config)
    {
        return $this->configuration;
    }
    
    public function getConfigurationEntry($cat, $key)
    {
        return $this->configuration[$cat][$key];
    }
    
    public function setEntity($entity)
    {
        $this->view->entity = $entity;
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