<?php

class BikesController {
    
    public function index()
    {
        GenericCode::checkUserPermission(['renter']);
        
        $unsortedBikes = Bike::getAllCurrentlyNonBooked();
        $bikes = $this->sortByUserPostalCode($unsortedBikes);
        $postalCode = $this->getPostalCodeOfUser();
        require_once(dirname(__DIR__).'/views/bikes/index.php');
    }
    
    public function registerBikeForm()
    {
        GenericCode::checkUserPermission(['owner']);
        
        require_once(dirname(__DIR__).'/views/bikes/register.php');
    }
    
    public function register()
    {
        GenericCode::checkUserPermission(['owner']);
        
        if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['price']) || !isset($_POST['postalCode'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
            
        } else {
            $title = GenericCode::stripHtmlCharacters($_POST["title"]);
            $description = GenericCode::stripHtmlCharacters($_POST["description"]);
            $price = GenericCode::stripHtmlCharacters($_POST["price"]);
            $postalCode = GenericCode::stripHtmlCharacters($_POST["postalCode"]);
    
            if (isset($_POST['id'])){
                $registeredBike = Bike::update($_POST['id'], $title, $description, $price, $postalCode);
            } else {
                $registeredBike = Bike::register($title, $description, $price, $postalCode);
            }
            
            if ($registeredBike){
                $name = $title;
                require_once(dirname(__DIR__).'/views/pages/success.php');
            } else {
                require_once(dirname(__DIR__).'/views/pages/error.php');
            }
        }
    }
    
    public function book()
    {
        GenericCode::checkUserPermission(['renter']);
        
        if (!isset($_POST['bikeId']) || !isset($_POST['endDate'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
            
        } else {
            $isBooked = Bike::book($_POST['bikeId'], $_POST['endDate']);
            if ($isBooked){
                $userId = Bike::getOwnerId($_POST['bikeId']);
                include dirname(__DIR__).'/models/user.php';
                $user = User::getContactInfo($userId);
                require_once(dirname(__DIR__).'/views/bikes/booking.php');
            } else {
                require_once(dirname(__DIR__).'/views/pages/error.php');
            }
        }
    }
    
    public function myBikes()
    {
        GenericCode::checkUserPermission(['owner']);
        
        $bikes = Bike::getOwnBikes();
        
        require_once(dirname(__DIR__).'/views/bikes/my_bikes.php');
    }
    
    public function getBike()
    {
        GenericCode::checkUserPermission(['owner', 'renter']);

        if (!isset($_GET['id']) && !isset($_GET['page'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
            
        } else {
            $bike = Bike::getOne($_GET['id']);
            
            if (($_GET['page'] == "edit") && GenericCode::checkUserPermission(['owner'], true)){
                require_once(dirname(__DIR__).'/views/bikes/edit.php');
                
            } elseif (($_GET['page'] == "book") && GenericCode::checkUserPermission(['renter'], true)){
                require_once(dirname(__DIR__).'/views/bikes/show.php');
    
            }
        }
    }
    
    /**
     * @return string
     */
    private function getPostalCodeOfUser(){
        return file_get_contents('https://ipapi.co/postal/', false);
    }
    
    /**
     * @param Bike[]
     * @return Bike[]
     */
    private function sortByUserPostalCode($bikes){
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