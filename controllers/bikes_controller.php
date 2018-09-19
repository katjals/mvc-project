<?php

class BikesController {

//    protected $usersController;
//
//    /**
//     * BikesController constructor.
//     * @param $usersController
//     */
//    public function __construct($usersController)
//    {
//        $usersController = new UsersController();
//    }
    
    public function index() {
        // we store all the bikes in a variable
        $unsortedBikes = Bike::getAllNonBooked();
        $bikes = $this->sortByUserPostalCode($unsortedBikes);
        $postalCode = $this->getPostalCodeOfUser();
        require_once(dirname(__DIR__).'/views/bikes/index.php');
    }
    
    public function show(){
        // we expect a url of form ?controller=bikess&actions=show&id=x
        // without an id we just redirect to the error page as we need the bike id to find it in the db
        if (!isset($_GET['id'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
            
        } else {
            // we use the given id to get the right bike
            $bike = Bike::getOne($_GET['id']);
            require_once(dirname(__DIR__).'/views/bikes/show.php');
        }
    }
    
    public function registerBikeForm(){
        require_once(dirname(__DIR__).'/views/bikes/register.php');
    }
    
    public function register(){
        if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['price']) || !isset($_POST['postalCode'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
            
        } else {
            $title = GenericCode::stripHtmlCharacters($_POST["title"]);
            $description = GenericCode::stripHtmlCharacters($_POST["description"]);
            $price = GenericCode::stripHtmlCharacters($_POST["price"]);
            $postalCode = GenericCode::stripHtmlCharacters($_POST["postalCode"]);
    
            $registeredBike = Bike::register($title, $description, $price, $postalCode);
            if ($registeredBike){
                $name = $title;
                require_once(dirname(__DIR__).'/views/pages/success.php');
            } else {
                require_once(dirname(__DIR__).'/views/pages/error.php');
            }
        }
    }
    
    public function book(){
        // without an id we just redirect to the error page as we need the bike id to find it in the db
        if (!isset($_POST['id'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
            
        } else {
            $isBooked = Bike::book($_POST['id']);
            if ($isBooked){
                //include 'users_controller.php';
               // $user = UsersController::getContactInfo($userId);
                $userId = Bike::getOwnerId($_POST['id']);
                include dirname(__DIR__).'/models/user.php';
                $user = User::getContactInfo($userId);
           //     $user2 = $usersController->getContactInfo($userId);
                require_once(dirname(__DIR__).'/views/bikes/booking.php');
            } else {
                require_once(dirname(__DIR__).'/views/pages/error.php');
            }
        }
    }
    
    private function getPostalCodeOfUser(){
        return file_get_contents('https://ipapi.co/postal/', false);
    }
    
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