<?php

namespace Luna\Expr;

abstract class Where{
    
    protected $removeOperator = false;
    
    public function removeOperator(){
        $this->removeOperator = true;
    }
}