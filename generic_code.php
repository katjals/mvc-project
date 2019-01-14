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
    public static function stripHtmlCharacters($string)
    {
        $string = trim($string);
        $string = stripslashes($string);
        $string = htmlspecialchars($string);
        return $string;
    }
    
    // TODO
    public static function stripSql()
    {
    }
    
    /**
     * @param  $roles
     */
    /**
     * @param string[] $roles
     * @param bool $returnBool
     * @return bool
     */
    public static function checkUserPermission($roles, $returnBool = false)
    {
        if (!isset($_SESSION['roles'])){
            require_once('views/pages/error.php');
            exit();
        }
        if (empty(array_intersect($roles, $_SESSION['roles'])) && $returnBool == false){
            require_once('views/pages/error.php');
            exit();
        } elseif (empty(array_intersect($roles, $_SESSION['roles'])) && $returnBool == true){
            return false;
        } elseif ($returnBool == true){
            return true;
        }
    }
}