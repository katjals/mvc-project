<?php

class GenericCode {
    
    
    /**
     * GenericCode constructor.
     */
    private function __construct()
    {
    }
    
    public static function testInput($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
}