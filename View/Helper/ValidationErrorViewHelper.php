<?php

namespace LWmvc\View\Helper;

class ValidationErrorViewHelper
{
    public function __construct($language="en")
    {
        $this->language = $language;
        $this->loadErrorMessages();
    }
    
    protected function loadErrorMessages()
    {
        $textCSV = dirname(__FILE__)."/I18N/".$this->language."/LWmvcErrors.csv";
        if (($handle = fopen($textCSV, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $this->text[$data[0]] = $data[1];
            }
            fclose($handle);
        }        
    }
    
    public function addCustomErrorMessages($array)
    {
        
    }
    
    public function getOutput($error)
    {
        if ($error) {
            $view = new \lw_view(dirname(__FILE__)."/Templates/LWmvcErrors.tpl.phtml");
            $view->error = $error;
            $view->text = $this->text;
            $view->lang = $this->language;
            return $view->render();
        }
    }
}