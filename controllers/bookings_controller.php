<?php

class BookingsController {
    
    public function index()
    {
        GenericCode::checkUserPermission(['renter']);

        $bookings = Booking::getMyBookings();
        
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
}