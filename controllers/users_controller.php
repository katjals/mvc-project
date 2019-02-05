<?php

class UsersController {
    
    public function createUserForm()
    {
        require_once(dirname(__DIR__).'/views/users/create.php');
    }
    
    public function loginPage()
    {
        require_once(dirname(__DIR__).'/views/users/login.php');
    }
    
    public function create()
    {
        if (!isset($_POST['name']) || !isset($_POST['password']) || !isset($_POST['phoneNumber'])
            || !isset($_POST['email']) || !isset($_POST['roles'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
        
        } else {
            $name = GenericCode::stripHtmlCharacters($_POST["name"]);
            $phoneNumber = GenericCode::stripHtmlCharacters($_POST["phoneNumber"]);
            $email = GenericCode::stripHtmlCharacters($_POST["email"]);
            $password = GenericCode::stripHtmlCharacters($_POST["password"]);
            
            $userId = User::create($name, $password, $phoneNumber, $email);
            if ($userId){
                //TODO set role enums
                User::setRoles($userId, $_POST['roles']);
                
                self::createSession($name, $userId, $_POST['roles']);
                
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
            
            if (isset($user)) {
                if ($password == $user['password']) {
                    // creates user based session and returns to index.php. Location will remove the get value, hence index redirect to home page
                    $roles = User::getRoles($user['id']);
                    self::createSession($user['name'], $user['id'], $roles);
        
                } else {
                    require_once(dirname(__DIR__) . '/views/pages/error.php');
                }
            }
        }
    }
    
    /**
     * @param string $name
     * @param int $id
     * @param string[] $roles
     */
    private function createSession($name, $id, $roles)
    {
        $_SESSION['username'] = $name;
        $_SESSION['id'] = $id;
        $_SESSION['roles'] = $roles;
        header( "Location: index.php" );
    }
    
    public function logout()
    {
        if ($_GET['action'] === 'logout' ){
            session_destroy();
            // returns to index.php. Location will remove the get value, hence index redirect to home page
            header( "Location: index.php" );
        }
    }
    
}