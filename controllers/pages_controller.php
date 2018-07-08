<?php

class PagesController {
    public function home() {
        $first_name = 'Jon';
        $las_name = 'Snow';
        require_once('views/pages/home.php');
    }
    
    public function error(){
        require_once('views/pages/error.php');
    }
}