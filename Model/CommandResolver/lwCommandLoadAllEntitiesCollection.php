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

namespace LWmvc\Model\CommandResolver;

class lwCommandLoadAllEntitiesCollection extends \LWmvc\Model\CommandResolver
{
    protected $command;
    
    public function __construct($command)
    {
        parent::__construct($command);
    }
    
    public function getInstance($command)
    {
        return new lwCommandLoadAllEntitiesCollection($command);
    }
    
    public function resolve()
    {
        if ($this->command->getParameterByKey('showDeletedEntries') == 1) {
            $items = $this->getQueryHandler()->loadAllEntries(true);
        }
        else {
            $items = $this->getQueryHandler()->loadAllEntries();
        }
        $collection = \LWmvc\Model\EntityCollectionFactory::buildCollectionFromQueryResult($this->ObjectClass, $items);
        $this->command->getResponse()->setDataByKey('allEntitiesCollection', $collection);
        return $this->command->getResponse();        
    }
}
