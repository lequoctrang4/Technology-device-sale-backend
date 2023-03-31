<?php
require_once __DIR__."/routes.php";
class Sever {
    public $route = null;
    public $config;
}

function newSever() {
    $res = new Sever();
    $res->route = setupRoute();
    // $res->config = 
    return $res;
}
?>