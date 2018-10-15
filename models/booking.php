<?php

class Booking {
    
    public $startTime;
    
    public $endTime;
    
    public $title;
    
    public function __construct($startTime, $endTime, $title)
    {
        $this->endTime = $endTime;
        $this->startTime = $startTime;
        $this->title = $title;
    }
    
    public static function getMyBookings()
    {
        $bookings = [];
        
        try {
            $db = Db::getInstance();
            $req = $db->prepare('SELECT booking.startTime, booking.endTime, bike.title FROM booking INNER JOIN bike ON booking.bikeId = bike.id WHERE booking.userId = :id');
            $req->execute(array('id' => $_SESSION['id']));
            $results = $req->fetchAll();
            
            foreach($results as $result){
                $bookings[] = new Booking(
                    $result['startTime'],
                    $result['endTime'],
                    $result['title']
                );
            }
            
            return $bookings;
            
        } catch (Exception $e){
            throw new Exception("DB error when fetching all bookings");
        }
        
    }
}