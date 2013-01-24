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

class Controller
{
    public function __construct($baseNamespace, $defaultAction)
    {
        $this->defaultAction = $defaultAction;
        $dicClass = $baseNamespace.'Services\dic';
        $this->dic = new $dicClass();
        $this->request = $this->dic->getLwRequest();
        $dispatchClass = $baseNamespace.'Domain\DomainEventDispatch';
        $this->dispatch = $dispatchClass::getInstance();
    }
    
    public function execute()
    {
        $methodName = $this->request->getAlnum('cmd')."Action";
        if (method_exists($this, $methodName)) {
            return call_user_method($methodName, $this);
        }
        else {
            return call_user_method($this->defaultAction, $this);
        }
    }
    
    protected function returnRenderedView($view)
    {
        $response = \LWddd\Response::getInstance();
        $response->setOutputByKey('output', $view->render());
        return $response;
    }
    
    protected function buildReloadResponse($array)
    {
        $response = \LWddd\Response::getInstance();
        foreach($array as $key => $value) {
            $response->setParameterByKey($key, $value);
        }
        return $response;
    }
    
    protected function executeDomainEvent($domain, $event, $parameterArray=array(), $dataArray=array())
    {
        $event = \LWddd\DomainEvent::getInstance($domain, $event);
        foreach($parameterArray as $key => $value) {
            $event->setParameterByKey($key, $value);
        }
        foreach($dataArray as $key => $value) {
            $event->setDataByKey($key, $value);
        }
        return $this->dispatch->execute($event);
    }
    
    protected function buildDeleteAction($domain, $id)
    {
        $response = $this->executeDomainEvent($domain, 'deleteById', array("id"=>$id));
        if ($response->getParameterByKey("deleted") === true) {
            return $this->buildReloadResponse(array("cmd"=>"showList", "response"=>2));
        }
        throw new \Exception($response->getDataByKey("errorMessage"));
    }
    
    protected function buildSaveAction($domain, $data, $id)
    {
        $response = $this->executeDomainEvent($domain, 'save', array("id"=>$id), array("postArray"=>$data));
        if ($response->getParameterByKey("error")) {
            return $this->editFormAction($response->getDataByKey("error"));
        }
        return $this->buildReloadResponse(array("cmd"=>"showList", "response"=>1));
    }
    
    protected function buildAddAction($domain, $data)
    {
        $response = $this->executeDomainEvent($domain, 'add', array(), array('postArray'=>$data));
        if ($response->getParameterByKey("error")) {
            return $this->addFormAction($response->getDataByKey("error"));
        }
        return $this->buildReloadResponse(array("cmd"=>"showList", "response"=>1));
    }
}