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
    
    public static function getAllNonBooked() {
        $list = [];
        
        try {
            $db = Db::getInstance();
            $req = $db->query('SELECT * FROM bike WHERE is_booked = FALSE ORDER BY postalCode');
    
            // we create a list of Bike objects from the db result
            foreach($req->fetchAll() as $bike){
                $list[] = new Bike($bike['title'], $bike['description'], $bike['price'], $bike['postalCode'], $bike['id']);
            }
    
            return $list;
            
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
    
            $req = $db->prepare('INSERT INTO bike(title, description, price, postalCode)
                                      VALUES(:title, :description, :price, :postalCode)');
            $req->execute(array(
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'postalCode' => $postalCode));
    
            return true;
            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
        
    }
    
    public static function book($id){
        
        try {
            $db = Db::getInstance();
            $req = $db->prepare('UPDATE bike SET is_booked = TRUE WHERE id = :id');
            // the query was prepared, now we replace :id with our actual $id value
            $req->execute(array('id' => $id));
    
            return true;
    
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
    
    public static function getOwnerId($bikeId){
        
        try {
            $db = Db::getInstance();
            $id = intval($bikeId);
            $req = $db->prepare('SELECT * FROM bike WHERE id = :id');
            $req->execute(array('id' => $id));
            $bike = $req->fetch();
            
            return $bike['user_id'];

            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
}