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

class lwCommandAdd extends \LWmvc\Model\CommandResolver
{
    protected $command;
    
    public function __construct($command)
    {
        parent::__construct($command);
    }
    
    public function getInstance($command)
    {
        return new lwCommandAdd($command);
    }
    
    public function resolve()
    {
        $dto = new \LWmvc\Model\DTO($this->command->getDataByKey('postArray'));
        $filter = \LWmvc\Model\FilterFactory::buildFilter($this->baseNamespace);
        $DTOFiltered = $filter->filter($dto);
        $entity = \LWmvc\Model\EntityFactory::buildEntityFromDTO($this->ObjectClass, $DTOFiltered);
        $isValidSpecification = \LWmvc\Model\SpecificationFactory::buildIsValidSpecification($this->baseNamespace);
        if ($isValidSpecification->isSatisfiedBy($entity)) {
            $id = $this->getCommandHandler()->addEntity($entity->getValues());
            $this->postSaveWork($id, $id, $entity);            
            $this->command->getResponse()->setParameterByKey('saved', true);
        }
        else {
            $this->command->getResponse()->setDataByKey('error', $isValidSpecification->getErrors());
            $this->command->getResponse()->setParameterByKey('error', true);
        }                    
        return  $this->command->getResponse();
    }
}
