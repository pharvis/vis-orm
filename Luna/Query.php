<?php

namespace Luna;

use Luna\Common\Obj;

class Query{
    
    protected $db = null;
    protected $sql = '';
    protected $parameters = [];
    
    public function __construct(Database $db, string $sql, array $parameters = []){
        $this->db = $db;
        $this->sql = $sql;
        $this->parameters = $parameters;
    }
    
    public function single(string $entityName){
        $row = $this->db->query($this->sql, $this->parameters)->fetch(\PDO::FETCH_ASSOC); 
        if($row){
            return $this->toEntity($entityName, $row);
        }
    }
    
    public function toList(string $entityName = '') : array{
        
        if(!$entityName){
            return $this->db->query($this->sql, $this->parameters)->fetchAll(\PDO::FETCH_OBJ);
        }
        
        $rows = $this->db->query($this->sql, $this->parameters)->fetchAll(\PDO::FETCH_ASSOC); 
        
        foreach($rows as $idx=> $row){
            $rows[$idx] = $this->toEntity($entityName, $row);
        }
        return $rows;
    }
    
    protected function toEntity(string $entityName, $data){
        return Obj::create($entityName)->setProperties($data)->get();
    }
}