<?php

class BikesController {
    
    public function index() {
        // we store all the bikes in a variable
        $unsortedBikes = Bike::all();
        $bikes = $this->sortByUserPostalCode($unsortedBikes);
        $postalCode = $this->getPostalCodeOfUser();
        require_once(dirname(__DIR__).'/views/bikes/index.php');
    }
    
    public function show(){
        // we expect a url of form ?controller=bikess&actions=show&id=x
        // without an id we just redirect to the error page as we need the bike id to find it in the db
        if (!isset($_GET['id'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
        }
        
        // we use the given id to get the right bike
        $bike = Bike::find($_GET['id']);
        require_once(dirname(__DIR__).'/views/bikes/show.php');
    }
    
    public function registerBikeForm(){
        require_once(dirname(__DIR__).'/views/bikes/register.php');
    }
    
    public function register(){
        if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['price']) || !isset($_POST['postalCode'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
        }
        
        $title = GenericCode::testInput($_POST["title"]);
        $description = GenericCode::testInput($_POST["description"]);
        $price = GenericCode::testInput($_POST["price"]);
        $postalCode = GenericCode::testInput($_POST["postalCode"]);
        
        $registeredBike = Bike::register($title, $description, $price, $postalCode);
        if ($registeredBike){
            $name = $title;
            require_once(dirname(__DIR__).'/views/pages/success.php');
        } else {
            require_once(dirname(__DIR__).'/views/pages/error.php');
        }
    }
    
    public function getPostalCodeOfUser(){
        return file_get_contents('https://ipapi.co/postal/', false);
    }
    
    public function sortByUserPostalCode($bikes){
        usort($bikes, function($a, $b){
            $postalCode = $this->getPostalCodeOfUser();
            if ($a->postalCode == $b->postalCode){
                return 0;
            } elseif (abs($postalCode - $a->postalCode) > abs($postalCode - $b->postalCode)){
                return 1;
            } else {
                return -1;
            }
        });
        return $bikes;
    }
    
}