<?php

namespace Luna\Expr;

class Select{
    
    protected $select = '';
    
    public function __construct(string $select){
        $this->select = $select;
    }
    
    public function __toString(){
        return 'SELECT ' . $this->select . ' ';
    } 
}

