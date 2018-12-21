<?php

namespace Luna\Expr;

class From{
    
    protected $from = '';
    
    public function __construct(string $from){
        $this->from = $from;
    }
    
    public function __toString(){
        return 'FROM ' . $this->from . ' ';
    } 
}

