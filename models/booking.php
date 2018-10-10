<?php

class Booking {
    
    public $startTime;
    
    public $endTime;
    
    public $bike;
    
    public function __construct($startTime, $endTime, $bike)
    {
        $this->endTime = $endTime;
        $this->startTime = $startTime;
        $this->bike = $bike;
    }
    
    
    public static function getMyBookings()
    {
        $bookings = [];
    
        try {
            $db = Db::getInstance();
            $req = $db->prepare('SELECT * FROM booking JOIN bike on booking.bikeId = bike.id WHERE userId = :id');
            $req->execute(array('id' => $_SESSION['id']));
            $results = $req->fetchAll();

            foreach($results as $result){
                $bookings[] = new Booking(
                    $result['booking']['startTime'],
                    $result['booking']['endTime'],
                    new Bike(
                        $result['bike']['title'],
                        $result['bike']['description'],
                        $result['bike']['price'],
                        $result['bike']['postalCode'],
                        $result['bike']['id'])
                );
            }
        
            return $bookings;
        
        } catch (Exception $e){
            throw new Exception("DB error when fetching all bikes");
        }

    }
}