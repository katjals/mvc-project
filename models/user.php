<?php

class User
{
    
    /**
     * @var int
     */
    public $id;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $password;
    
    /**
     * @var int
     */
    public $phoneNumber;
    
    /**
     * @var string
     */
    public $email;
    
    /**
     * User constructor.
     * @param string $name
     * @param int $phoneNumber
     */
    public function __construct($name, $phoneNumber)
    {
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
    }
    
    /**
     * @param string $name
     * @param string $password
     * @param string $phoneNumber
     * @param string $email
     * @return int $userId
     * @throws Exception
     */
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
    
            $req = $db->prepare('SELECT LAST_INSERT_ID() FROM user');
            $req->execute();
            $userId = $req->fetch()[0];
            
            return $userId;
            
        } catch (Exception $e) {
            throw new Exception("DB error when creating " . $name);
        }
    }
    
    /**
     * @param int $userId
     * @param string[] $roles
     * @throws Exception
     */
    public static function setRoles($userId, $roles)
    {
        try {
            $db = Db::getInstance();
    
            foreach ($roles as $role){
                $req = $db->prepare('SELECT id FROM role WHERE role = :role');
                $req->execute(array('role' => $role));
                $roleId = $req->fetch()[0];
    
                $req = $db->prepare('INSERT INTO user_role(userId, roleId) VALUES(:userId, :roleId)');
                $req->execute(array(
                    'roleId' => $roleId,
                    'userId' => $userId));
            }
        } catch (Exception $e) {
            throw new Exception("Db error on setting roles");
        }
    }
    
    /**
     * @param string $email
     * @return mixed
     * @throws Exception
     */
    public static function login($email)
    {
        try {
            $db = Db::getInstance();
            
            //TODO only the first role is fetched
            $req = $db->prepare('SELECT *
                                          FROM user
                                          INNER JOIN user_role ON user.id = user_role.userId
                                          INNER JOIN role ON user_role.roleId = role.id
                                          WHERE user.email = :email');

            $req->execute(array('email' => $email));
            $user = $req->fetch();
    
            //echo'<pre>';print_r($user);echo'</pre>';
            
            return $user;
        } catch (Exception $e) {
            throw new Exception("DB error caused by login of user with email: " . $email);
        }
    }
    
    /**
     * @param int $userId
     * @return User
     * @throws Exception
     */
    public static function getContactInfo($userId)
    {
        try {
            $db = Db::getInstance();
            // we make sure $id is an integer
            $id = intval($userId);
            $req = $db->prepare('SELECT name,phoneNumber FROM user WHERE id = :id');
            // the query was prepared, now we replace :id with our actual $id value
            $req->execute(array('id' => (int)$userId));
            $user = $req->fetch();
            
            return new User($user['name'], $user['phoneNumber']);
            
        } catch (Exception $e) {
            throw new Exception("DB error when finding bike with id: " . $userId);
        }
    }
}