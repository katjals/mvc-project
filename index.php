<?php
session_start();

require_once('connection.php');
require_once('generic_code.php');

if (isset($_GET['controller']) && isset($_GET['action'])){
    $controller = $_GET['controller'];
    $action = $_GET['action'];
} else {
    $controller = 'pages';
    $action = 'home';
}

require_once('views/layout.php');