<?php declare(strict_types = 1);
    error_reporting(E_ALL);
    // ini_set('display_errors', 1);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: *");
    session_start();
    // require_once  './core/App.php';
    require __DIR__.'/core/Bootstrap.php';
    // $myApp = new App();
?>
