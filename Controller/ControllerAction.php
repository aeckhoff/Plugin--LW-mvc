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

namespace LWmvc\Controller;

class ControllerAction
{
    
    protected $defaultAction;
    protected $dic;
    protected $request;
    protected $dispatch;
    protected $command;
    
    public function __construct($package)
    {
        $dicClass = "\\".$package.'\\Services\\dic';
        $this->dic = new $dicClass();
        $this->request = $this->dic->getLwRequest();
        $this->config = $this->dic->getConfiguration();
    }
    
    protected function returnRenderedView($view)
    {
        $response = \LWmvc\Model\Response::getInstance();
        $response->setOutputByKey('output', $view->render());
        return $response;
    }
    
    protected function buildReloadResponse($array)
    {
        $response = \LWmvc\Model\Response::getInstance();
        foreach($array as $key => $value) {
            $response->setParameterByKey($key, $value);
        }
        return $response;
    }
    
    protected function executeDomainEvent($package, $domain, $eventName, $parameterArray=array(), $dataArray=array())
    {
        $event = \LWddd\DomainEvent::getInstance($domain, $eventName);
        foreach($parameterArray as $key => $value) {
            $event->setParameterByKey($key, $value);
        }
        foreach($dataArray as $key => $value) {
            $event->setDataByKey($key, $value);
        }
        $class = "\\".$package."\\Domain\\".$domain."\\EventResolver\\".$eventName;
        return $class::getInstance($event)->resolve();        
    }
}