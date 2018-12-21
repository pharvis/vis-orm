<?php

namespace Luna;

use Luna\Common\Str;
use Luna\Common\Arr;

class EntityMetaReader{
    
    public function getEntityMeta(string $entityName) : EntityMetaData{
        $reflect = new \ReflectionClass($entityName);
        
        $segments = Str::set($reflect->getDocComment())->split(PHP_EOL);
        $array = new Arr();
        
        foreach($segments->toStringGenerator() as $segment){
            if($segment->trim('/* ')->startsWith('@')){
                $tokens = token_get_all('<?php ' . (string)$segment->trim('@') . ' ?>'); 

                foreach($tokens as $token){
                    if (is_array($token)){
                        switch($token[0]){
                            case T_STRING:
                                $key = $token[1];
                                $array->add($key, null); 
                                break;
                            case T_CONSTANT_ENCAPSED_STRING: 
                                $array->add($key, trim($token[1], '"'));
                                break;
                        }
                    }
                }
            }
        }
        
        return new EntityMetaData($entityName, $array->get('table'), $array->get('key'));
    }
}

