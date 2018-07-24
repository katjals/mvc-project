<?php

class BikesController {
    public function index() {
        // we store all the posts in a variable
        $bikes = Bike::all();
        require_once(dirname(__DIR__).'/views/bikes/index.php');
    }
    
    public function show(){
        // we expect a url of form ?controller=posts&actions=show&id=x
        // without an id we just redirect to the error page as we need the post id to find it in the db
        if (!isset($_GET['id'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
        }
        
        // we use the given id to get the right post
        $bike = Bike::find($_GET['id']);
        require_once(dirname(__DIR__).'/views/bikes/show.php');
    }
    
    public function registerBikeForm(){
        require_once(dirname(__DIR__).'/views/bikes/register.php');
    }
    
    public function register(){
        // we expect a url of form ?controller=posts&actions=show&id=x
        // without an id we just redirect to the error page as we need the post id to find it in the db
        if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['price']) || !isset($_POST['postalCode'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
        }
        
        $title = $this->testInput($_POST["title"]);
        $description = $this->testInput($_POST["description"]);
        $price = $this->testInput($_POST["price"]);
        $postalCode = $this->testInput($_POST["postalCode"]);
        
        // we use the given id to get the right post
        $registeredBike = Bike::register($title, $description, $price, $postalCode);
        if ($registeredBike){
            $name = $title;
            require_once(dirname(__DIR__).'/views/pages/success.php');
        } else {
            require_once(dirname(__DIR__).'/views/pages/error.php');
        }
    }
    
    public function getPostalCodeOfUser(){
        # the postal code of the user, to be used to get bikes
        $opts = array('http'=>array('method'=>"GET", 'header'=>"User-Agent: mybot.v0.7.1"));
        $context = stream_context_create($opts);
    
        return file_get_contents('https://ipapi.co/postal/', false, $context);
    }
    
    function testInput($data) {
        $genericController = new GenericCode();
        $genericController->testInput($data);
        return $data;
    }
}