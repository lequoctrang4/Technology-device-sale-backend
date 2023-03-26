<?php 
class App 
{
    public $sever = null;
    protected $controller = "Product";
    protected $action = "products";
    protected $params = [];
    function __construct() {
        require_once(__DIR__."/Sever.php");
        $this->sever = newSever();
        // $arr = $this->UrlProcess();
        // print_r($arr);
        // // require_once __DIR__."/../../src/controllers/Admin.php";
        // if (!empty($arr) && file_exists(__DIR__."/../controllers/".$arr[0].".php")) {
        //     $this->controller = $arr[0];
        //     unset($arr[0]);
        // }
        // // require_once "../controllers/".$this->controller.".php";
        // require_once __DIR__."/../../src/controllers/".$this->controller.".php";
        
        // if (!empty($arr) && isset($arr[1])) {
        //     if (method_exists($this->controller, $arr[1])) {
        //         $this->action = $arr[1];
        //     }
        //     unset($arr[1]);
        // }
        // $this->params = !empty($arr)?array_values(($arr)) : [];
        // call_user_func_array([new $this->controller, $this->action], $this->params);

    }
}

?>