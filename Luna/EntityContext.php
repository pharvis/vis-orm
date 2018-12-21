<?php

namespace Luna;

class EntityContext{
    
    protected $entity = null;
    protected $entityName = '';
    protected $hashCode = '';
    protected $state = 1;
    
    const STATE_INSERT = 1;
    const STATE_UPDATE = 2;
    const STATE_DELETE = 3;

    public function __construct($entity){
        $this->entity = $entity;
        $this->entityName = get_class($entity);
        $this->hashCode = spl_object_hash($entity);
    }
    
    public function getEntity(){
        return $this->entity;
    }
    
    public function getEntityName() : string{
        return $this->entityName;
    }

    public function getHashCode() : string{
        return $this->hashCode;
    }
    
    public function setState(int $state){
        $this->state = $state;
        return $this;
    }
    
    public function getState() : int{
        return $this->state;
    }
}