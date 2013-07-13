<?php

namespace LWmvc\Model;

class DataQueryHandler
{
    protected $db;
    
    public function __construct(\lw_db $db)
    {
        $this->db = $db;
    }
    
    protected function useLwExtendedColumns($bool)
    {
        if ($bool) {
            $this->useExtendedColumns = true;
        }
        else {
            $this->useExtendedColumns = false;
        }
    }
    
    protected function setBaseOrderColumnAndDirection($column, $direction) 
    {
        $this->orderColumn = $column;
        $this->orderDirection = $direction;
    }
    
    public function loadAllEntries($showDeletedColumns=false)
    {
        if ($this->useExtendedColumns == true && $showDeletedColumns != true) {
            $this->db->setStatement("SELECT * FROM ".$this->table." WHERE ( lw_deleted < 1 OR lw_deleted IS NULL ) ORDER BY ".$this->orderColumn." ".$this->orderDirection);
        }
        else {
            $this->db->setStatement("SELECT * FROM ".$this->table." ORDER BY ".$this->orderColumn." ".$this->orderDirection);
        }
        $results = $this->db->pselect();
        foreach($results as $result) {
            $array[] = new \LWmvc\Model\DTO($result);
        }
        return $array;
    }

    /**
     * Returns all saved data for a specific event
     * @param int $id
     * @return array
     */
    public function loadEntryById($id, $showDeletedColumns=false)
    {
        if ($this->useExtendedColumns == true && $showDeletedColumns != true) {
            $this->db->setStatement("SELECT * FROM ".$this->table." WHERE id = :id AND ( lw_deleted < 1 OR lw_deleted IS NULL ) ");
        }
        else {
            $this->db->setStatement("SELECT * FROM ".$this->table." WHERE id = :id");
        }
        $this->db->bindParameter("id", "i", $id);
        $result = $this->db->pselect1();
        return new \LWmvc\Model\DTO($result);
    }    
}
