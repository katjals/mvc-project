<?php

class PagesController {
    //todo are these used?
    
    public function home()
    {
        require_once(dirname(__DIR__).'/views/pages/home.php');
    }
    
    public function error()
    {
        require_once(dirname(__DIR__).'/views/pages/error.php');
    }
}