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
        
        } else {
            $name = GenericCode::stripHtmlCharacters($_POST["name"]);
            $phoneNumber = GenericCode::stripHtmlCharacters($_POST["phoneNumber"]);
            $email = GenericCode::stripHtmlCharacters($_POST["email"]);
            $password = GenericCode::stripHtmlCharacters($_POST["password"]);
    
            $createdUser = User::create($name, $password, $phoneNumber, $email);
            if ($createdUser){
                require_once(dirname(__DIR__).'/views/pages/success.php');
            } else {
                require_once(dirname(__DIR__).'/views/pages/error.php');
            }
        }
    }
    
    public function login()
    {
        if (isset($_POST['email']) && isset($_POST['psw'])) {
            $email = GenericCode::stripHtmlCharacters($_POST["email"]);
            $password = GenericCode::stripHtmlCharacters($_POST["psw"]);
    
            $user = User::login($email);
            
            if (isset($user)){
                $name = $user['name'];
                $psw = $user['password'];
                $id = $user['id'];
    
                if ($password == $psw){
                    // creates user based session and returns to index.php. Location will remove the get value, hence index redirect to home page
                    $_SESSION['username'] = $name;
                    $_SESSION['userId'] = $id;
                    header( "Location: index.php" );
                } else {
                    require_once(dirname(__DIR__).'/views/pages/error.php');
                }
            }
        }
    }
    
    public function logout()
    {
        if ($_GET['action'] === 'logout' ){
            session_destroy();
            // returns to index.php. Location will remove the get value, hence index redirect to home page
            header( "Location: index.php" );
        }
        
    }
    
    /**
     * @param $userId
     * @return User
     */
    public static function getContactInfo($userId){
        include_once(dirname(__DIR__).'/models/user.php');
    
        $user = User::getContactInfo($userId);
        return $user;
    }
    
}