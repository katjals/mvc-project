<?php

class UsersController {
    
    public function createUserForm(){
        require_once(dirname(__DIR__).'/views/users/create.php');
    }
    
    public function loginPage(){
        require_once(dirname(__DIR__).'/views/users/login.php');
    }
    
    public function create(){
        // we expect a url of form ?controller=posts&actions=show&id=x
        // without an id we just redirect to the error page as we need the post id to find it in the db
        if (!isset($_POST['name']) || !isset($_POST['password']) || !isset($_POST['phoneNumber']) || !isset($_POST['email'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
        }
        
        $name = $this->testInput($_POST["name"]);
        $phoneNumber = $this->testInput($_POST["phoneNumber"]);
        $email = $this->testInput($_POST["email"]);
        $password = $this->testInput($_POST["password"]);
    
        // we use the given id to get the right post
        $createdUser = User::create($name, $password, $phoneNumber, $email);
        if ($createdUser){
            require_once(dirname(__DIR__).'/views/pages/success.php');
        } else {
            require_once(dirname(__DIR__).'/views/pages/error.php');
        }
    }
    
    public function login()
    {
        if (isset($_POST['email']) && isset($_POST['psw'])) {
            $email = $this->testInput($_POST["email"]);
            $password = $this->testInput($_POST["psw"]);
    
            $user = User::login($email);
            
            if (isset($user)){
                $name = $user['name'];
                $psw = $user['password'];
    
                if ($password == $psw){
                    require_once(dirname(__DIR__).'/views/pages/success.php');
                } else {
                    require_once(dirname(__DIR__).'/views/pages/error.php');
                }
            }
        }
    }
    
    function testInput($data) {
        $genericController = new GenericCode();
        $genericController->testInput($data);
        return $data;
    }
    
    
}