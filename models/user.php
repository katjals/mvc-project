<?php

class User {
    
    public $id;
    
    public $name;
    
    public $password;
    
    public $phoneNumber;
    
    public $email;
    
    /**
     * User constructor.
     * @param $id
     * @param $name
     * @param $password
     * @param $phoneNumber
     * @param $email
     */
    public function __construct($name, $password, $phoneNumber = null, $email = null, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
    }
    
    public static function create($name, $password, $phoneNumber, $email){
        $db = Db::getInstance();
    
//        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $req = $db->prepare('INSERT INTO user(name, password, phoneNumber, email)
                                      VALUES(:name, :password, :phoneNumber, :email)');
        // the query was prepared, now we replace :id with our actual $id value
        $req->execute(array(
            'name' => $name,
            'password' => $password,
            'phoneNumber' => $phoneNumber,
            'email' => $email));
    
        return true;
    }
    
    public static function login($email){
        $db = Db::getInstance();
        
        $req = $db->prepare('SELECT password,name FROM user WHERE email = :email');
        $req->execute(array('email' => $email));
        $user = $req->fetch();
    
        return $user;
    }
    
}