<?php

class Booking {
    
    /**
     * @var string
     */
    public $id;
    
    /**
     * @var string
     */
    public $startTime;
    
    /**
     * @var string
     */
    public $endTime;
    
    /**
     * @var string
     */
    public $bikeTitle;
    
    /**
     * @var int
     */
    public $bikeId;
    
    /**
     * Booking constructor.
     * @param string $startTime
     * @param string $endTime
     * @param string|null $bikeTitle
     * @param int|null $bikeId
     * @param int|null $id
     */
    public function __construct($startTime, $endTime, $bikeTitle = null, $bikeId = null, $id = null)
    {
        $this->endTime = $endTime;
        $this->startTime = $startTime;
        $this->bikeTitle = $bikeTitle;
        $this->bikeId = $bikeId;
        $this->id = $id;
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
            $req = $db->prepare('SELECT booking.startTime, booking.endTime, bike.title, booking.id
                                          FROM booking
                                          INNER JOIN bike ON booking.bikeId = bike.id
                                          WHERE booking.userId = :id');
            $req->execute(array('id' => $_SESSION['id']));
            $results = $req->fetchAll();
            
            foreach($results as $result){
                $bookings[] = new Booking(
                    (new DateTime($result['startTime']))->format("d. M Y H:i"),
                    (new DateTime($result['endTime']))->format("d. M Y H:i"),
                    $result['title'],
                    null,
                    $result['id']
                );
            }
            
            return $bookings;
            
        } catch (Exception $e){
            throw new Exception("DB error when fetching all bookings");
        }
    }
    
    /**
     * @param Booking $booking
     * @return bool
     * @throws Exception
     */
    public static function book($booking)
    {
        try {
            $db = Db::getInstance();
            $req = $db->prepare('INSERT INTO booking(startTime, endTime, userId, bikeId)
                                          VALUES(:startTime, :endTime, :userId, :bikeId)');
            $req->execute([
                'startTime' => (new DateTime($booking->startTime))->format('Y-m-d H:i'),
                'endTime' => (new DateTime($booking->endTime))->format('Y-m-d H:i'),
                'userId' => $_SESSION['id'],
                'bikeId' => $booking->bikeId
            ]);
            
            return true;
            
        } catch (Exception $e){
            throw new Exception("DB error when creating new bike");
        }
    }
}