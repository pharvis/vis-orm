<?php

namespace Luna;

use Luna\Common\Obj;
use Luna\Common\Str;
use Luna\Common\Arr;

class EntityManager{
    
    private $db = null;
    private $metaReader = null;
    private $metaCollection = null;
    private $repositories = null;
    private $entities = null;

    public function __construct(string $dns, string $username, string $password, array $options = []){
        $this->db = new Database($dns, $username, $password, $options);
        $this->metaReader = new EntityMetaReader();
        $this->metaCollection = new Arr();
        $this->repositories = new Arr();
        $this->entities = new Arr();
    }
    
    public function getDatabase() : Database{
        return $this->db;
    }
    
    public function getRepository(string $repositoryName){

        if($this->repositories->exists($repositoryName)){
            return $this->repositories->get($repositoryName);
        }

        $repositoryClass = $repositoryName . 'Repository';

        if(class_exists($repositoryClass)){
            $repository = new $repositoryClass($this);
        }else{
            $repository = new EntityRepository($this, $repositoryName);
        }
        return $repository;
    }

    public function getMetaCollection() : Arr{
        return $this->metaCollection;
    }
    
    public function getMetaReader() : EntityMetaReader{
        return $this->metaReader;
    }
    
    public function createQueryBuilder() : QueryBuilder{
        return new QueryBuilder($this);
    }
    
    public function query(string $sql, array $parameters = []){
        return new Query($this->getDatabase(), $sql, $parameters);
    }

    public function persist($entity){
        $entityContext = new EntityContext($entity);
        
        if(!$this->metaCollection->exists($entityContext->getEntityName())){
            $this->metaCollection->add($entityContext->getEntityName(), $this->metaReader->getEntityMeta($entityContext->getEntityName()));
        }
        
        $this->entities->add($entityContext->getHashCode(), $entityContext);
        return $entityContext;
    }

    public function saveChanges(){
        foreach($this->entities as $entityContext){
            $metadata = $this->metaCollection->get($entityContext->getEntityName());
            $entity = $entityContext->getEntity();

            $properties = Obj::from($entity)->getProperties();
            $data = [];

            foreach($properties as $property => $value){
                if(false == Str::set($property)->startsWith('_')){
                    
                    if(is_object($value)){
                        $hashCode = spl_object_hash($value);
                        
                        if($this->entities->exists($hashCode)){
                            $parentEntity = $this->entities->get($hashCode);
                            $parentMetadata = $this->metaCollection->get($parentEntity->getEntityName());
                            $value = Obj::from($parentEntity->getEntity())->getProperty($parentMetadata->getPrimaryKey());
                        }
                    }
                    $data[$property] = $value;
                }
            }
            
            switch($entityContext->getState()){
                case EntityContext::STATE_INSERT:
                    $this->db->insert($metadata->getTableName(), $data);
                    $primaryKeyValue = $this->db->lastInsertId();
                    Obj::from($entity)->setProperty($metadata->getPrimaryKey(), $primaryKeyValue);
                    $entityContext->setState(EntityContext::STATE_UPDATE);
                    break;
                
                case EntityContext::STATE_UPDATE:
                    $primaryKeyValue = Obj::from($entity)->getProperty($metadata->getPrimaryKey());
                    $this->db->update($metadata->getTableName(), $data, [$metadata->getPrimaryKey() => $primaryKeyValue]);
                    break;
            }
        }
    }
}