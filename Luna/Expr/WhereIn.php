<?php

namespace Luna\Expr;

class WhereIn extends Where{
    
    protected $field = '';
    protected $condition = '';
    
    public function __construct(string $field, array $condition){
        $this->field = $field;
        $this->condition = $condition;
    }
    
    public function __toString(){
        $string = $this->field . ' IN('; 
        
        foreach($this->condition as $idx => $condition){
            $name = 'c_' . $this->field . '_' . $idx;
            $this->addParameter($name, $condition);
            $string .= ':'.$name.',';
        }

        return trim($string, ',') . ')';
    } 
}

