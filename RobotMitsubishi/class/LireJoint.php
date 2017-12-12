<?php

class LireJoint {
    
    private $stringArray;
    
    public function __construct(){
        $this->stringArray = array("command" => 0, "sendType" => 2, "recvType" => 2, "sendIOType" => 0, "recvIOType" => 0, "count" => 0);
    }
    
    public function getStringArray(){
        return $this->stringArray;
    }
    
}



