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

class lwCommandGetEntryById extends \LWmvc\Model\CommandResolver
{
    protected $command;
    
    public function __construct($command)
    {
        parent::__construct($command);
    }
    
    public function getInstance($command)
    {
        return new lwCommandGetEntryById($command);
    }
    
    public function resolve()
    {
        if ($this->command->getParameterByKey('showDeletedEntries') == 1) {
            $dto = $this->getQueryHandler()->loadEntryById($this->command->getParameterByKey("id"), true);
        }
        else {
            $dto = $this->getQueryHandler()->loadEntryById($this->command->getParameterByKey("id"));
        }
        if ($this->command->getParameterByKey("decorated") == true) {
            $config = $this->command->getParameterByKey('configuration');
            $decorator = \LWmvc\Model\DecoratorFactory::buildDecorator($this->baseNamespace);
            $dto = $decorator->decorate($dto);
        }
        $entry = \LWmvc\Model\EntityFactory::buildEntity($this->ObjectClass, $dto, $this->command->getParameterByKey("id"));
        $this->command->getResponse()->setDataByKey('Entity', $entry);
        return $this->command->getResponse();        
    }
}
