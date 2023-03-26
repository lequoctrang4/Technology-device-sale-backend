<?php 
function callback($controller, $method, $params) {
    if (empty($controller) || !file_exists(__DIR__."/../controllers/".$controller.".php")) {
        echo "Controller not exists";
        exit();
    }    
    require_once __DIR__."/../controllers/".$controller.".php";
    if (empty($method) || !method_exists($controller, $method)) {
        echo "Controller:|". $controller ."|doesn't have method:|".$method."|</br>";
        exit();
    }
    call_user_func_array([new $controller, $method], $params);
}
?>