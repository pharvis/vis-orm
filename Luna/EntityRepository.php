<?php

namespace Luna;

class EntityRepository{

    protected $em = null;
    protected $entityMeta;
    
    public function __construct(EntityManager $em, string $entityName){
        $this->em = $em;
        
        $metaCollection = $this->em->getMetaCollection();
        
        if(!$metaCollection->exists($entityName)){
            $metaCollection->add($entityName, $this->em->getMetaReader()->getEntityMeta($entityName));
        }
        $this->entityMeta = $this->em->getMetaCollection()->get($entityName);
    }
    
    public function getEntityManger(): EntityManager{
        return $this->em;
    }
    
    public function find(int $id){
        $entity = $this->em->createQueryBuilder()
            ->select('*')
            ->from($this->entityMeta->getTableName())
            ->andWhere($this->entityMeta->getPrimaryKey() .'=:id')
            ->addParameter('id', $id)
            ->execute()
            ->single($this->entityMeta->getEntityName());
        
        $this->em->persist($entity)->setState(EntityContext::STATE_UPDATE);
        return $entity;
    }
    
    public function findAll(){
        $entities = $this->em->createQueryBuilder()
            ->select('*')
            ->from($this->entityMeta->getTableName())
            ->execute()
            ->toList($this->entityMeta->getEntityName());
        
        foreach($entities as $entity){
            $this->em->persist($entity)->setState(EntityContext::STATE_UPDATE);
        }
        return $entities;
    }
    
    public function findOneBy(array $criteria){
        $qb = $this->em->createQueryBuilder()
            ->select('*')
            ->from($this->entityMeta->getTableName());
        
            foreach($criteria as $column => $value){
                $qb->andWhere($column.'=:'.$column);
                $qb->addParameter($column, $value);
            }
        
        $entity = $qb->execute()
            ->single($this->entityMeta->getEntityName());
        
        $this->em->persist($entity)->setState(EntityContext::STATE_UPDATE);
        return $entity;
    }
    
    public function findBy(array $criteria){
        $qb = $this->em->createQueryBuilder()
            ->select('*')
            ->from($this->entityMeta->getTableName());
        
            foreach($criteria as $column => $value){
                $qb->andWhere($column.'=:'.$column);
                $qb->addParameter($column, $value);
            }
        
        $entities = $qb->execute()
            ->toList($this->entityMeta->getEntityName());
        
        foreach($entities as $entity){
            $this->em->persist($entity)->setState(EntityContext::STATE_UPDATE);
        }
        return $entities;
    }
}
