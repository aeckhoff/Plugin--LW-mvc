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

namespace LWmvc\Model;

class Decorator
{
    public function getInstance()
    {
        return new Decorator();
    }
    
    public function decorate(\LWmvc\Model\DTO $dto)
    {
        $values = $dto->getValues();
        foreach($values as $key => $value){
            $value = trim($value);
            $method = $key.'Decorate';
            if (method_exists($this, $method)) {
                $value = $this->$method($value);
            }
            $decoratedValues[$key] = $value;
        }
        return new \LWmvc\Model\DTO($decoratedValues);
    }
}