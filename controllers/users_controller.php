<?php

class UsersController {
    
    public function createUserForm(){
        require_once(dirname(__DIR__).'/views/users/create.php');
    }
    
    public function loginPage(){
        require_once(dirname(__DIR__).'/views/users/login.php');
    }
    
    public function create(){
        if (!isset($_POST['name']) || !isset($_POST['password']) || !isset($_POST['phoneNumber']) || !isset($_POST['email'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
        }
        
        $name = GenericCode::testInput($_POST["name"]);
        $phoneNumber = GenericCode::testInput($_POST["phoneNumber"]);
        $email = GenericCode::testInput($_POST["email"]);
        $password = GenericCode::testInput($_POST["password"]);
    
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
            $email = GenericCode::testInput($_POST["email"]);
            $password = GenericCode::testInput($_POST["psw"]);
    
            $user = User::login($email);
            
            if (isset($user)){
                $name = $user['name'];
                $psw = $user['password'];
    
                if ($password == $psw){
                    require_once(dirname(__DIR__).'/views/pages/success.php');
                    $_SESSION['username'] = $name;
                } else {
                    require_once(dirname(__DIR__).'/views/pages/error.php');
                }
            }
        }
    }
    
    public function logout()
    {
        session_destroy();
        require_once(dirname(__DIR__).'/views/pages/home.php');
    }
    
}