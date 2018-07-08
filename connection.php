<?php

class Db {
    private static $instance = null;
    
    private function __construct(){}
    
    private function __clone() {
        // TODO: Implement __clone() method.
    }
    
    public static function getInstance() {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            self::$instance = new PDO('mysql:host=localhost;dbname=php_mvc',
                'root', '', $pdo_options);
        }
    }
}