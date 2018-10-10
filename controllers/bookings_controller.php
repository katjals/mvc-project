<?php

class BookingsController {
    
    public function index() {
        // we store all the bookings in a variable
        $bookings = Bike::getMyBookings();
        require_once(dirname(__DIR__).'/views/bookings/index.php');
    }
}