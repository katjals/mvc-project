<?php

class PagesController {
    
    public function home()
    {
        require_once(dirname(__DIR__).'/views/pages/home.php');
    }
    
    //todo make it accept error message like success
    public function error()
    {
        require_once(dirname(__DIR__).'/views/pages/error.php');
    }
    
    //todo make message more generic
    public function success($name)
    {
        require_once(dirname(__DIR__).'/views/pages/success.php');
    }
}