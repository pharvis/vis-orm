<?php

namespace Luna\Expr;

class AndWhere extends Where{
    
    protected $condition = '';
    
    public function __construct(string $condition){
        $this->condition = $condition;
    }
    
    public function __toString(){
        return ($this->removeOperator ? $this->condition : 'AND ' . $this->condition) .  ' ';
    } 
}

