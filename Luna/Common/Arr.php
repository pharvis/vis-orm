<?php

namespace Luna\Common;

final class Arr implements \IteratorAggregate{
    
    private $collection = [];
    
    public function __construct(array $collection = []){
        $this->collection = $collection;
    }
    
    public function add(string $key, $value){
        $this->collection[$key] = $value;
        return $this;
    }
    
    public function addIndex($value){
        $this->collection[] = $value;
        return $this;
    }
    
    public function get(string $key){
        if($this->exists($key)){
            return $this->collection[$key];
        }
    }
    
    public function exists(string $key){
        return array_key_exists($key, $this->collection);
    }
    
    public function toStringGenerator(){
        foreach($this->collection as $item){
            if(is_String($item)){
                yield new Str($item);
            }
        }
    }
    
    public function getIterator(){
        return new \ArrayIterator($this->collection);
    }
}