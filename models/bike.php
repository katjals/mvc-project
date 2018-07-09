<?php

class Bike {
    
    // we define 3 attributes
    // they are public so that we can access them using $post->author directly
    public $id;
    
    public $title;
    
    public $description;
    
    public $price;
    
    public function __construct($id, $title, $description, $price){
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
    }
    
    public static function all() {
        $list = [];
        $db = Db::getInstance();
        $req = $db->query('SELECT * FROM bike');
        
        // we create a list of Post objects from the db result
        foreach($req->fetchAll() as $bike){
            $list[] = new Bike($bike['id'], $bike['title'], $bike['description'], $bike['price']);
        }
        
        return $list;
    }
    
    public static  function find($id){
        $db = Db::getInstance();
        // we make sure $id is an integer
        $id = intval($id);
        $req = $db->prepare('SELECT * FROM bike WHERE id = :id');
        // the query was prepared, now we replace :id with our actual $id value
        $req->execute(array('id' => $id));
        $bike = $req->fetch();
        
        return new Bike($bike['id'], $bike['title'], $bike['description'], $bike['price']);
    }
}