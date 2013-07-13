<?php

namespace LWmvc\View\Helper;

class ForeignKeySelectViewHelper
{
    protected $EntryIdentifier = "id";
    protected $EntryLabel = "name";
    protected $Collection;
    protected $FieldId;

    public function __construct()
    {
    }

    public function setEntryIdentifier($id)
    {
        $this->EntryIdentifier = $id;
    }
    
    public function setEntryLabel($label)
    {
        $this->EntryLabel = $label;
    }
    
    public function getOutput($Collection, $FieldId, $value)
    {
        $view = new \lw_view(dirname(__FILE__)."/Templates/ForeignKeySelect.tpl.phtml");
        $view->Collection = $Collection;
        $view->FieldId = $FieldId;
        $view->EntryValue = $value;
        $view->EntryIdentifier = "id";
        $view->EntryLabel = $this->EntryLabel;
        return $view->render();
    }
}
