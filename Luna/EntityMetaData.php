<?php

namespace Luna;

class EntityMetaData{
    
    protected $entityName = '';
    protected $tableName = '';
    protected $primaryKey = '';
    
    public function __construct(string $entityName, string $tableName, string $primaryKey){
        $this->entityName = $entityName;
        $this->tableName = $tableName;
        $this->primaryKey = $primaryKey;
    }
    
    public function getEntityName(): string{
        return $this->entityName;
    }
    
    public function getTableName(): string{
        return $this->tableName;
    }
    
    public function getPrimaryKey(): string{
        return $this->primaryKey;
    }
}

