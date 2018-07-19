<?php

class PagesController {
    public function home() {
        $first_name = 'Jon';
        $last_name = 'Snow';
        require_once(dirname(__DIR__).'/views/pages/home.php');
    }
    
    public function error(){
        require_once(dirname(__DIR__).'/views/pages/error.php');
    }
}