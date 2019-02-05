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
    
            $locations = Bike::getAllNonBooked($_SESSION['startDate'], $_SESSION['endDate']);
            
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
        GenericCode::checkUserPermission(['owner']);
        
        if (!empty($_POST['title']) || !empty($_POST['description']) || !empty($_POST['price'])){
            $title = GenericCode::stripHtmlCharacters($_POST["title"]);
            $description = GenericCode::stripHtmlCharacters($_POST["description"]);
            $price = GenericCode::stripHtmlCharacters($_POST["price"]);
    
            if (isset($_POST['id'])){
                $bike = new Bike($title, $description, $price, $_POST['id']);
                Bike::update($bike);
                
                if(!empty($_POST['lat']) || !empty($_POST['lon'])){
                    $address = $this->stripHtmlAddress();
                    Address::update($address);
                }
           
            } elseif (!empty($_POST['lat']) || !empty($_POST['lon'])) {
                $address = $this->stripHtmlAddress();
                $addressId = Address::register($address);
                
                $bike = new Bike($title, $description, $price, null,
                    new Address(null, null, null, null, null, null, $addressId));
                //TODO use setter instead
                Bike::register($bike);
                
            } else {
                require_once(dirname(__DIR__).'/views/pages/error.php');
            }
    
            $name = $title;
            require_once(dirname(__DIR__).'/views/pages/success.php');
            
        } else {
            require_once(dirname(__DIR__).'/views/pages/error.php');
        }
    }
    
    /**
     * @return Address
     */
    public function stripHtmlAddress()
    {
        $streetNumber = GenericCode::stripHtmlCharacters($_POST["streetNumber"]);
        $streetName = GenericCode::stripHtmlCharacters($_POST["streetName"]);
        $city = GenericCode::stripHtmlCharacters($_POST["city"]);
        $postalCode = GenericCode::stripHtmlCharacters($_POST["postalCode"]);
        $country = GenericCode::stripHtmlCharacters($_POST["country"]);
        $lat = GenericCode::stripHtmlCharacters($_POST["lat"]);
        $lon = GenericCode::stripHtmlCharacters($_POST["lon"]);
    
        $street = $streetName . " " . $streetNumber;
        if (!empty($_POST['addressId'])){
            $address = new Address($postalCode, $city, $street, $country, $lat, $lon, $_POST['addressId']);
    
        } else {
            $address = new Address($postalCode, $city, $street, $country, $lat, $lon);
        }
        
        return $address;
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

        if (empty($_GET['id']) || empty($_GET['page'])){
            require_once(dirname(__DIR__).'/views/pages/error.php');
            
        } else {
            $bike = Bike::getOne($_GET['id']);
            
            if (($_GET['page'] == "edit") && GenericCode::checkUserPermission(['owner'], true)){
                $address = Address::getBikeAddress($_GET['id']);
                require_once(dirname(__DIR__).'/views/bikes/register.php');
                
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