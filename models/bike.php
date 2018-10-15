<?php

class Bike {
    
    /**
     * @var int
     */
    public $id;
    
    /**
     * @var string
     */
    public $title;
    
    /**
     * @var string
     */
    public $description;
    
    /**
     * @var int
     */
    public $price;
    
    /**
     * @var int
     */
    public $postalCode;
    
    /**
     * Bike constructor.
     * @param string $title
     * @param string $description
     * @param int $price
     * @param int $postalCode
     * @param int|null $id
     */
    public function __construct($title, $description, $price, $postalCode, $id = null){
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->postalCode = $postalCode;
    }
    
    /**
     * @return Bike[]
     * @throws Exception
     */
    public static function getAllCurrentlyNonBooked(){
        $bikes = [];
        
        try {
            $db = Db::getInstance();
            $req = $db->prepare('SELECT * FROM bike WHERE id NOT IN (SELECT booking.bikeId FROM booking WHERE :today BETWEEN booking.startTime AND booking.endTime)');
            $req->execute(array('today' => (new DateTime('now'))->format('Y-m-d')));
            $results = $req->fetchAll();
            
            // we create a list of Bike objects from the db result
            foreach($results as $bike){
                $bikes[] = new Bike($bike['title'], $bike['description'], $bike['price'], $bike['postalCode'], $bike['id']);
            }
            
            return $bikes;
            
        } catch (Exception $e){
            throw new Exception("DB error when fetching all bikes");
        }
    }
    
    /**
     * @param int $bikeId
     * @return Bike
     * @throws Exception
     */
    public static function getOne($bikeId){
        
        try {
            $db = Db::getInstance();
            // we make sure $id is an integer
            $id = intval($bikeId);
            $req = $db->prepare('SELECT * FROM bike WHERE id = :id');
            // the query was prepared, now we replace :id with our actual $id value
            $req->execute(array('id' => $bikeId));
            $bike = $req->fetch();
    
            return new Bike($bike['title'], $bike['description'], $bike['price'], $bike['postalCode'], $bike['id']);
    
        } catch (Exception $e) {
            throw new Exception("DB error when finding bike with id: ".$id);
        }
    }
    
    /**
     * @return Bike[]
     * @throws Exception
     */
    public static function getOwnBikes()
    {
        $bikes = [];
    
        try {
            $db = Db::getInstance();
            $req = $db->prepare('SELECT * FROM bike WHERE userId = :userId');
            $req->execute(array('userId' => $_SESSION['id']));
            
            // we create a list of Bike objects from the db result
            foreach($req->fetchAll() as $bike){
                $bikes[] = new Bike($bike['title'], $bike['description'], $bike['price'], $bike['postalCode'], $bike['id']);
            }
        
            return $bikes;
        
        } catch (Exception $e){
            throw new Exception("DB error when fetching users own bikes");
        }
    }
    
    /**
     * @param string $title
     * @param string $description
     * @param int $price
     * @param int $postalCode
     * @return bool
     * @throws Exception
     */
    public static function register($title, $description, $price, $postalCode)
    {
        try {
            $db = Db::getInstance();
    
            $req = $db->prepare('INSERT INTO bike(title, description, price, postalCode, userId)
                                      VALUES(:title, :description, :price, :postalCode, :userId)');
            $req->execute(array(
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'postalCode' => $postalCode,
                'userId' => $_SESSION['id']
            ));
    
            return true;
            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
    
    /**
     * @param int $bikeId
     * @return bool
     * @throws Exception
     */
    public static function book($bikeId)
    {
        try {
            $db = Db::getInstance();
            $req = $db->prepare('INSERT INTO booking(startTime, endTime, userId, bikeId)
                                      VALUES(:startTime, :endTime, :userId, :bikeId)');
            $req->execute(array(
                'startTime' => (new DateTime('now'))->format('Y-m-d'),
                'endTime' => ((new DateTime('now'))->add(new DateInterval('P1D')))->format('Y-m-d'),
                'userId' => $_SESSION['id'],
                'bikeId' => $bikeId
            ));
            
            return true;
    
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
    
    /**
     * @param int $bikeId
     * @return int
     * @throws Exception
     */
    public static function getOwnerId($bikeId){
        
        try {
            $db = Db::getInstance();
            $id = intval($bikeId);
            $req = $db->prepare('SELECT * FROM bike WHERE id = :id');
            $req->execute(array('id' => $id));
            $bike = $req->fetch();
            
            return $bike['userId'];

            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
}