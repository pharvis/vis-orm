<?php

namespace Luna\Expr;

abstract class Where{
    
    protected $parameters = [];
    protected $removeOperator = false;

    public function removeOperator(){
        $this->removeOperator = true;
    }
    
    public function addParameter($name, $value){
        $this->parameters[$name] = $value;
        return $this;
    }
    
    public function getParameters(){
        return $this->parameters;
    }
}