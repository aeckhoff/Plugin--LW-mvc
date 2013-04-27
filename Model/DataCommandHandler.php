<?php

namespace LWmvc\Model;

class DataCommandHandler
{
    protected $db;
    protected $ActualDate;
    protected $UserId = -1;
    protected $useExtendedColumns = false;
    
    public function __construct(\lw_db $db)
    {
        $this->db = $db;
        $this->ActualDate = date('YmdHis');
    }
    
    public function setUserId($id)
    {
        $this->UserId = $id;
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
    
    public function addData($saveArray)
    {
        if ($this->useExtendedColumns) {
            $saveArray[] = array("lw_first_date", "date", "s", $this->ActualDate);
            $saveArray[] = array("lw_first_user", "user", "s", $this->UserId);
            $saveArray[] = array("lw_last_date", "date", "s", $this->ActualDate);
            $saveArray[] = array("lw_last_user", "user", "s", $this->UserId);
        }
        foreach($saveArray as $field) {
            if (strlen($column)>0) {
                $column = $column.", ";
                $value = $value.", ";
            }
            $column.= ' '.$field[0].' ';
            $value.= ' :'.$field[1].' ';
        }
        $this->db->setStatement("INSERT INTO ".$this->table." (".$column.") VALUES (".$value.") ");
        foreach($saveArray as $field) {
            $this->db->bindParameter($field[1], $field[2], $field[3]);
        }
        return $this->db->pdbinsert($this->table);
    }
    
    public function saveDataById($id, $saveArray)
    {
        if ($this->useExtendedColumns) {
            $saveArray[] = array("lw_last_date", "date", "s", $this->ActualDate);
            $saveArray[] = array("lw_last_user", "user", "s", $this->UserId);
        }
        foreach($saveArray as $field) {
            if (strlen($set)>0) {
                $set = $set.", ";
            }
            $set.= ' '.$field[0].' = :'.$field[1].' ';
        }
        $this->db->setStatement("UPDATE ".$this->table." SET ".$set." WHERE id = :id ");
        foreach($saveArray as $field) {
            $this->db->bindParameter($field[1], $field[2], $field[3]);
        }
        $this->db->bindParameter("id", "i", $id);
        return $this->db->pdbquery();
    }
    
    public function deleteEntity($id)
    {
        if ($this->useExtendedColumns) {
            $this->db->setStatement("UPDATE ".$this->table." SET lw_last_date = :date, lw_last_user = :user, lw_deleted = :deleted WHERE id = :id ");
            $this->db->bindParameter("id", "i", $id);
            $this->db->bindParameter("deleted", "s", $this->ActualDate);
            $this->db->bindParameter("date", "s", $this->ActualDate);
            $this->db->bindParameter("user", "s", $this->UserId);
        }
        else {
            $this->db->setStatement("DELETE FROM ".$this->table." WHERE id = :id ");
            $this->db->bindParameter("id", "i", $id);
        }
        return $this->db->pdbquery();
    }    
}