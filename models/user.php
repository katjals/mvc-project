<?php

class User
{
    
    public $id;
    
    public $name;
    
    public $password;
    
    public $phoneNumber;
    
    public $email;
    
    /**
     * User constructor.
     * @param $name
     * @param $phoneNumber
     */
    public function __construct($name, $phoneNumber)
    {
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
    }
    
    public static function create($name, $password, $phoneNumber, $email)
    {
        
        try {
            $db = Db::getInstance();
            
            $req = $db->prepare('INSERT INTO user(name, password, phoneNumber, email)
                                      VALUES(:name, :password, :phoneNumber, :email)');
            $req->execute(array(
                'name' => $name,
                'password' => $password,
                'phoneNumber' => $phoneNumber,
                'email' => $email));
            
            return true;
            
        } catch (Exception $e) {
            throw new Exception("DB error when creating " . $name);
        }
    }
    
    public static function login($email)
    {
        
        try {
            $db = Db::getInstance();
            
            $req = $db->prepare('SELECT password,name FROM user WHERE email = :email');
            $req->execute(array('email' => $email));
            $user = $req->fetch();
            
            return $user;
        } catch (Exception $e) {
            throw new Exception("DB error caused by login of user with email: " . $email);
        }
    }
    
    public static function getContactInfo($userId)
    {
        
        try {
            $db = Db::getInstance();
            // we make sure $id is an integer
            $id = intval($userId);
            $req = $db->prepare('SELECT name,phoneNumber FROM user WHERE id = :id');
            // the query was prepared, now we replace :id with our actual $id value
            $req->execute(array('id' => $userId));
            $user = $req->fetch();
            
            return new User($user['name'], $user['phoneNumber']);
            
        } catch (Exception $e) {
            throw new Exception("DB error when finding bike with id: " . $userId);
        }
    }
}