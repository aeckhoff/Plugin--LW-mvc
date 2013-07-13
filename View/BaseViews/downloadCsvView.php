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

namespace LWmvc\View\BaseViews;

class downloadCsvView extends \LWmvc\View\View
{
    protected $view;
    protected $aggregate;
    
    public function __construct($role=false)
    {
        parent::__construct();
    }

    public function filterOutput($value) 
    {
        $value = strip_tags($value);
        $value = str_replace('"', "'", $value);
        $value = str_replace(';', ',', $value);
        return $value;
    }
    
    public function render()
    {
        foreach($this->view->collection as $entity) {
            foreach($entity->getValues() as $key => $value) {
                if ($headerdone != 1) {
                    if ($header) {
                        $header = $header.";";
                    }
                    $header.= '"'.$key.'"';
                }
                if ($line) {
                    $line = $line.";";
                }
                $line.= '"'.$this->filterOutput($value).'"';
            }
            if ($headerdone != 1) {
                $header.= PHP_EOL;
            }
            $headerdone = 1;
            $body.= $line.PHP_EOL;
            unset($line);
        }
        header("Pragma: public");
        header("Expires: 0");
        header('Content-Description: File Transfer');
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/octet-stream;");
        header('Content-disposition: attachment; filename="'.date('YmdHis').'_teilnehmerliste.csv"');
        print chr(255).chr(254).mb_convert_encoding($header.$body, 'UTF-16LE', 'UTF-8');
        die();
    }  
}
