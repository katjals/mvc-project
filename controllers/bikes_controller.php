<?php

class BikesController {
    
    public function index()
    {
        GenericCode::checkUserPermission(['renter']);
    
        if (!isset($_POST['dates'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
    
        } else {
            $separatedDates = explode(" to ",$_POST['dates']);
            $_SESSION['startDate'] = $separatedDates[0];
            $_SESSION['endDate'] = $separatedDates[1];
            
//            $unsortedBikes = Bike::getAllNonBooked($startDate, $endDate);
//            $bikes = $this->sortByUserPostalCode($unsortedBikes);
//            $postalCode = $this->getPostalCodeOfUser();
    
            $xmlFile = Bike::getAllNonBooked($_SESSION['startDate'], $_SESSION['endDate']);
            
            require_once(dirname(__DIR__).'/views/bikes/select_time.php');
        }
    }
    
    public function registerBikeForm()
    {
        GenericCode::checkUserPermission(['owner']);
        
        require_once(dirname(__DIR__).'/views/bikes/register.php');
    }
    
    public function register()
    {
        echo'<pre>';print_r($_POST);echo'</pre>';
    
    
        GenericCode::checkUserPermission(['owner']);
        
        if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['price']) || empty($_POST['lat']) || empty($_POST['lon'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
            
        } else {
            $title = GenericCode::stripHtmlCharacters($_POST["title"]);
            $description = GenericCode::stripHtmlCharacters($_POST["description"]);
            $price = GenericCode::stripHtmlCharacters($_POST["price"]);
            $streetNumber = GenericCode::stripHtmlCharacters($_POST["streetNumber"]);
            $streetName = GenericCode::stripHtmlCharacters($_POST["streetName"]);
            $city = GenericCode::stripHtmlCharacters($_POST["city"]);
            $postalCode = GenericCode::stripHtmlCharacters($_POST["postalCode"]);
            $country = GenericCode::stripHtmlCharacters($_POST["country"]);
            $lat = GenericCode::stripHtmlCharacters($_POST["lat"]);
            $lon = GenericCode::stripHtmlCharacters($_POST["lon"]);
            
            if (isset($_POST['id'])){
                $registeredBike = Bike::update($_POST['id'], $title, $description, $price, $postalCode);
            } else {
                $registeredBike = Bike::register($title, $description, $price, $streetNumber,
                    $streetName, $city, $postalCode, $country, $lat, $lon);
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
        
        if (!isset($_POST['bikeId']) || !isset($_POST['endDate']) || !isset($_POST['startDate'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
            
        } else {
            $isBooked = Bike::book($_POST['bikeId'], $_POST['endDate'], $_POST['startDate']);
            if ($isBooked){
                $userId = Bike::getOwnerId($_POST['bikeId']);
                include dirname(__DIR__).'/models/user.php';
                $user = User::getContactInfo($userId);
                include dirname(__DIR__).'/models/address.php';
                $address = Address::getBikeAddress($_POST['bikeId']);
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
    
    public function selectTime()
    {
        GenericCode::checkUserPermission(['renter']);
        
        require_once(dirname(__DIR__).'/views/bikes/select_time.php');
    }
    
    public function getBike()
    {
        GenericCode::checkUserPermission(['owner', 'renter']);

        if (!isset($_GET['id']) || !isset($_GET['page'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
            
        } else {
            $bike = Bike::getOne($_GET['id']);
            
            if (($_GET['page'] == "edit") && GenericCode::checkUserPermission(['owner'], true)){
                require_once(dirname(__DIR__).'/views/bikes/edit.php');
                
            } elseif (($_GET['page'] == "book") && GenericCode::checkUserPermission(['renter'], true)
                && isset($_SESSION['startDate']) && isset($_SESSION['endDate'])){
                $startDate = $_SESSION['startDate'];
                $endDate = $_SESSION['endDate'];

                require_once(dirname(__DIR__).'/views/bikes/show.php');
    
            } else {
                require_once(dirname(__DIR__).'/views/pages/error.php');
    
            }
        }
    }
    
//    /**
//     * @return string
//     */
//    private function getPostalCodeOfUser(){
//        return file_get_contents('https://ipapi.co/postal/', false);
//    }
//
//    /**
//     * @param Bike[]
//     * @return Bike[]
//     */
//    private function sortByUserPostalCode($bikes){
//        usort($bikes, function($a, $b){
//            $postalCode = $this->getPostalCodeOfUser();
//            if ($a->postalCode == $b->postalCode){
//                return 0;
//            } elseif (abs($postalCode - $a->postalCode) > abs($postalCode - $b->postalCode)){
//                return 1;
//            } else {
//                return -1;
//            }
//        });
//        return $bikes;
//    }
    
}