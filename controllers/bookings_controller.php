<?php

class BookingsController {
    
    public function index()
    {
        GenericCode::checkUserPermission(['renter']);

        $bookings = Booking::getMyBookings();
        
        $previousBookings = [];
        $futureBookings = [];
        foreach ($bookings as $booking) {
            if (strtotime($booking->endTime) < strtotime('now')) {
                $previousBookings[] = $booking;
            } else {
                $futureBookings[] = $booking;
            }
        }
        
        require_once(dirname(__DIR__).'/views/bookings/index.php');
    }
    
    public function book()
    {
        GenericCode::checkUserPermission(['renter']);
        
        if (!isset($_POST['bikeId']) || !isset($_POST['endDate']) || !isset($_POST['startDate'])){
            call('pages', 'error');
            
        } else {
            $booking = new Booking($_POST['startDate'], $_POST['endDate'], null, $_POST['bikeId']);
            $isBooked = Booking::book($booking);
            if ($isBooked){
                $userId = Bike::getOwnerId($_POST['bikeId']);
                $user = User::getContactInfo($userId);
                $address = Address::getBikeAddress($_POST['bikeId']);
                require_once(dirname(__DIR__).'/views/bikes/booking.php');
            } else {
                call('pages', 'error');
            }
        }
    }
    
    //TODO make sure bikes is available
    private function ensureBikeIsAvailable()
    {
    
    }
}