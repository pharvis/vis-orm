<?php

namespace Luna\Common;

class Obj{
    
    private $object = null;
    
    public function __construct($object){
        $this->object = $object;
    }
    
    public function setProperty(string $propertyName, $value){

        $reflect = new \ReflectionObject($this->object);
        $property = $reflect->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($this->object, $value);
        return $this;
    }
    
    public function getProperty(string $propertyName){
        
        $reflect = new \ReflectionObject($this->object);
        $property = $reflect->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($this->object);
    }
    
    public function setProperties(array $properties = []){
        $reflect = new \ReflectionObject($this->object);
        foreach($properties as $key=>$value){
            if($reflect->hasProperty($key)){ 
                $property = $reflect->getProperty($key);
                $property->setAccessible(true);
                $property->setValue($this->object, $value);
            }
        }
        return $this;
    }
    
    public function getProperties() : array{

        $reflect = new \ReflectionObject($this->object);

        $properties = $reflect->getProperties();
        $array = array();

        foreach($properties as $property){
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this->object);
        }
        return $array;
    }
    
    public function get(){
        return $this->object;
    }
    
    public static function create(string $className, array $args = []){
        $reflect = new \ReflectionClass(str_replace('.', '\\', $className));

        if(count($args) > 0){
            return  new Obj($reflect->newInstanceArgs($args));
        }else{
            return new Obj($reflect->newInstance());
        }
    }

    public static function from($object){
        return new Obj($object);
    }
}