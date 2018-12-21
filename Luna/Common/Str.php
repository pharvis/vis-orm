<?php

namespace Luna\Common;

final class Str{
    
    private $string = '';
    
    public function __construct(string $string){
        $this->string = $string;
    }
    
    public function append(string $string){
        $this->string .= $string;
        return $this;
    }
    
    public function split(string $pattern, int $limit = null, int $flags = PREG_SPLIT_NO_EMPTY){
        return new Arr(preg_split('@'.$pattern.'@', $this->string, $limit, $flags));
    }
    
    public function startsWith(string $string) : bool{
        return substr($this->string, 0, strlen($string)) == $string ? true : false;
    }
    
    public function trim($charmask = null){
        $this->string = trim($this->string, ((null == $charmask) ? " \t\n\r\0\x0B" : " \t\n\r\0\x0B" . $charmask));
        return $this;
    }
    
    public static function set(string $string){
        return new Str($string);
    }
    
    public function __toString(){
        return $this->string;
    }
}