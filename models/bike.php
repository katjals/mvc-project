<?php

class Bike {
    
    public $id;
    
    public $title;
    
    public $description;
    
    public $price;
    
    public $postalCode;
    
    public function __construct($title, $description, $price, $postalCode, $id = null){
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->postalCode = $postalCode;
    }
    
    public static function getAllNonBooked(){
        $bikes = [];
        
        try {
            $db = Db::getInstance();
            $req = $db->query('SELECT * FROM bike WHERE isBooked = FALSE ORDER BY postalCode');
    
            // we create a list of Bike objects from the db result
            foreach($req->fetchAll() as $bike){
                $bikes[] = new Bike($bike['title'], $bike['description'], $bike['price'], $bike['postalCode'], $bike['id']);
            }
    
            return $bikes;
            
        } catch (Exception $e){
            throw new Exception("DB error when fetching all bikes");
        }
        
    }
    
    public static function getOne($id){
        
        try {
            $db = Db::getInstance();
            // we make sure $id is an integer
            $id = intval($id);
            $req = $db->prepare('SELECT * FROM bike WHERE id = :id');
            // the query was prepared, now we replace :id with our actual $id value
            $req->execute(array('id' => $id));
            $bike = $req->fetch();
    
            return new Bike($bike['title'], $bike['description'], $bike['price'], $bike['postalCode'], $bike['id']);
    
        } catch (Exception $e) {
            throw new Exception("DB error when finding bike with id: ".$id);
        }
    }
    
    public static function register($title, $description, $price, $postalCode){
        
        try {
            $db = Db::getInstance();
    
            $req = $db->prepare('INSERT INTO bike(title, description, price, postalCode, isBooked, userId)
                                      VALUES(:title, :description, :price, :postalCode, :isBooked, :userId)');
            $req->execute(array(
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'postalCode' => $postalCode,
                'isBooked' => false,
                'userId' => $_SESSION['id']
            ));
    
            return true;
            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
        
    }
    
    public static function book($bikeId){
        
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
    
/*    public static function book($id){
        
        try {
            $db = Db::getInstance();
            $req = $db->prepare('UPDATE bike SET isBooked = TRUE WHERE id = :id');
            // the query was prepared, now we replace :id with our actual $id value
            $req->execute(array('id' => $id));
            
            return true;
            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }*/
    
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