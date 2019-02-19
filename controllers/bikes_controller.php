<?php

class BikesController {
    
    public function index()
    {
        GenericCode::checkUserPermission(['renter']);
    
        if (empty($_POST['dates'] || empty($_POST['lat']) || empty($_POST['lon']))){
            call('pages', 'error');
    
        } else {
            $separatedDates = explode(" to ",$_POST['dates']);
            $_SESSION['startDate'] = $separatedDates[0];
            $_SESSION['endDate'] = $separatedDates[1];
            $lat = GenericCode::stripHtmlCharacters($_POST["lat"]);
            $lon = GenericCode::stripHtmlCharacters($_POST["lon"]);
            $radius = GenericCode::stripHtmlCharacters($_POST["radius"]);
    
            $locations = Bike::getAllNonBooked($_SESSION['startDate'], $_SESSION['endDate'], $lat, $lon, $radius);
            
            require_once(dirname(__DIR__).'/views/bikes/results.php');
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
                $bikeId = GenericCode::stripHtmlCharacters($_POST['id']);
                $this->ensureBikeBelongsToUser($bikeId);
                $bike = new Bike($title, $description, $price, $bikeId);
                $addressId = Bike::update($bike);
                
                if(!empty($_POST['lat']) || !empty($_POST['lon'])){
                    $address = $this->stripHtmlAddress();
                    Address::update($address, $addressId);
                }
           
            } elseif (!empty($_POST['lat']) || !empty($_POST['lon'])) {
                $address = $this->stripHtmlAddress();
                $addressId = Address::register($address);
                
                $bike = new Bike($title, $description, $price, null,
                    new Address(null, null, null, null, null, null, $addressId));
                //TODO use setter instead
                Bike::register($bike);
                
            } else {
                call('pages', 'error');
            }
    
            call('pages', 'success', $title);
            
        } else {
            call('pages', 'error');
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
        
        return new Address($postalCode, $city, $street, $country, $lat, $lon);
    }
    
    /**
     * @param int $bikeId
     */
    private function ensureBikeBelongsToUser($bikeId)
    {
        $bikes = Bike::getOwnBikes();
        
        if (!isset($bikes[$bikeId])) {
            call('pages', 'error');
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

        if (empty($_GET['id']) || empty($_GET['page'])){
            call('pages', 'error');
            
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
                call('pages', 'error');
    
            }
        }
    }
    
}