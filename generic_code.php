<?php

class GenericCode {
    
    /**
     * GenericCode constructor.
     */
    private function __construct()
    {
    }
    
    /**
     * @param $string
     * @return string
     */
    public static function stripHtmlCharacters($string){
        $string = trim($string);
        $string = stripslashes($string);
        $string = htmlspecialchars($string);
        return $string;
    }
    
    // TODO
    public static function stripSql(){
    
    }
}