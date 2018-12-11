<?php

class BookingsController {
    
    public function index()
    {
        GenericCode::checkUserPermission(['renter']);

        $bookings = Booking::getMyBookings();
        
        require_once(dirname(__DIR__).'/views/bookings/index.php');
    }
}