<?php

class Db {
    private static $instance = null;
    
    private function __construct(){}
    
    private function __clone() {}
    
    public static function getInstance() {
        if (!isset(self::$instance)) {
            
            try {
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
                self::$instance = new PDO('mysql:host=localhost;dbname=hobby_project',
                    'root', '', $pdo_options);
                
            } catch (PDOException $e){
                echo 'Could not connect : '. $e->getMessage();
            }
        }
        return self::$instance;
    }
}