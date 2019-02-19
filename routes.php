<?php

function call($controller, $action, $param = null){
    // require the file that matches the controller name
    require_once('controllers/' . $controller . '_controller.php');
    
    // create a new instance of the needed controller
    switch($controller) {
        case 'pages':
            $controller = new PagesController();
            break;
        case 'bikes':
            require_once('models/bike.php');
            require_once('models/address.php');
            $controller = new BikesController();
            break;
        case 'users':
            require_once('models/user.php');
            $controller = new UsersController();
            break;
        case 'bookings':
            require_once('models/booking.php');
            require_once('models/bike.php');
            require_once('models/address.php');
            require_once('models/user.php');
            $controller = new BookingsController();
            break;
    }
    
    // call the action
    $controller->{$action}($param);
}

// a list of the controllers and their actions
// we consider those "allowed" values
$controllers = array('pages' => ['home', 'error', 'success'],
                     'bikes' => ['index', 'registerBikeForm', 'register', 'myBikes', 'getBike', 'selectTime'],
                     'users' => ['createUserForm', 'create', 'loginPage', 'login', 'logout'],
                     'bookings' => ['index', 'book']
    );

// check that the requested controller and action are both allowed
// if someone tries to access something else he will be redirected to the error action of the page
if (array_key_exists($controller, $controllers)){
    if (in_array($action, $controllers[$controller])) {
        call($controller, $action);
    } else {
        call('pages', 'error');
    }
} else {
    call('pages', 'error');
}