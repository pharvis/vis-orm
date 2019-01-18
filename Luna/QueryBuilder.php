<?php

namespace Luna;

use Luna\Common\Str;
use Luna\Common\Arr;

class QueryBuilder{
    
    protected $em = null;
    protected $sql = null;
    protected $parameters = [];

    public function __construct(EntityManager $em){
        $this->em = $em;
        $this->sql = new Arr();
    }
    
    public function select($select){
        $this->sql->addIndex(new Expr\Select($select));
        return $this;
    }
    
    public function from($table){
        $this->sql->addIndex(new Expr\From($table));
        return $this;
    }
    
    public function andWhere($condition){
        $this->sql->addIndex(new Expr\AndWhere($condition));
        return $this;
    }
    
    public function whereIn(string $field, array $criteria){
        $this->sql->addIndex(new Expr\WhereIn($field, $criteria));
        return $this;
    }
    
    public function addParameter($name, $value){
        $this->parameters[$name] = $value;
        return $this;
    }
    
    public function addParameters(array $parameters){
        $this->parameters = array_merge($this->parameters, $parameters);
        return $this;
    }
    
    public function sql(){
        $query = new Str('');
        $hasWhere = false;
        
        foreach($this->sql as $expr){
            if($expr instanceof Expr\Where){
                if(false == $hasWhere){
                    $query->append('WHERE ');
                    $expr->removeOperator();
                }
                $query->append($expr);
                $hasWhere = true;
                $this->addParameters($expr->getParameters());
            }else{
                $query->append($expr);
            }
        }
        return (string)$query;
    }

    public function execute(){ 
        return new Query($this->em->getDatabase(), $this->sql(), $this->parameters);
    }
}