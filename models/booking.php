<?php

class Booking {
    
    /**
     * @var string
     */
    public $startTime;
    
    /**
     * @var string
     */
    public $endTime;
    
    /**
     * Bike title
     * @var string
     */
    public $title;
    
    /**
     * Booking constructor.
     * @param string $startTime
     * @param string $endTime
     * @param string $title
     */
    public function __construct($startTime, $endTime, $title)
    {
        $this->endTime = $endTime;
        $this->startTime = $startTime;
        $this->title = $title;
    }
    
    /**
     * @return Booking[]
     * @throws Exception
     */
    public static function getMyBookings()
    {
        $bookings = [];
        
        try {
            $db = Db::getInstance();
            $req = $db->prepare('SELECT booking.startTime, booking.endTime, bike.title FROM booking
                                          INNER JOIN bike ON booking.bikeId = bike.id WHERE booking.userId = :id');
            $req->execute(array('id' => $_SESSION['id']));
            $results = $req->fetchAll();
            
            foreach($results as $result){
                $bookings[] = new Booking(
                    (new DateTime($result['startTime']))->format("d. M Y H:i"),
                    (new DateTime($result['endTime']))->format("d. M Y H:i"),
                    $result['title']
                );
            }
            
            return $bookings;
            
        } catch (Exception $e){
            throw new Exception("DB error when fetching all bookings");
        }
        
    }
}