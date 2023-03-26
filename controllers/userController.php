<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}
$path = explode('/', parse_url($_SERVER["REQUEST_URI"])["path"]);
require_once __DIR__ . '/../vendor/autoload.php';
require_once "../middlewares/auth.php";
require_once("../models/userModel.php");
use Firebase\JWT\JWT;

$method = $_SERVER["REQUEST_METHOD"];
try{
    if (!isset($path[3])){
                   throw new Exception("Cannot find route!",400);
                }
    switch ($method){
        case "GET":
            if (!isset(apache_request_headers()["Authorization"]) || !preg_match('/Bearer\s(\S+)/', apache_request_headers()["Authorization"], $matches)) {
                throw new Exception("Cannot find token!",400);
            }
            $user = authenticate($matches[1]);
            
            switch ($path[3]) {
                case 'profile':
                    echo json_encode(UserModel::getUserProfile($user["mobile"]));
                    break;
            }
            break;
        case "POST":
            switch($path[3]){
                case "login":
                    $mobile = $_POST["mobile"];
                    $password = $_POST["password"];
                    if (!UserModel::checkUserExistence($mobile)){
                        throw new Exception("User has not signed up yet!", 400);
                    }
                    if (!UserModel::comparePassword($mobile, $password)){
                        throw new Exception("Your password is incorrect!", 400);
                    }
                    $key = "lequoctrang";
                    $user = UserModel::getUserProfile($mobile);
                    $date = new DateTimeImmutable();
                    $expire_at = $date->modify('+5 days')->getTimestamp();
                    $payload = Array("id"=>$user["id"], "mobile"=>$user["mobile"],"email"=>$user["email"],"expire_at"=>$expire_at);
                    $jwt = JWT::encode($payload, $key, 'HS256');
                    $user["token"] = $jwt;
                    echo json_encode($user);
                    break;
                case "signup":
                    if (!isset($_POST["firstName"]) || !isset($_POST["middleName"]) || !isset($_POST["lastName"]) || !isset($_POST["mobile"]) || !isset($_POST["email"]) || !isset($_POST["password"]))
                            throw new Exception("Lack information to create new account", 400);
                    $fname = $_POST["firstName"];
                    $mname = $_POST["middleName"];
                    $lname = $_POST["lastName"];
                    $mobile = $_POST["mobile"];
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    if(UserModel::checkUserExistence($mobile)){
                        throw new Exception("User has already existed!", 400);
                    }
                    $hashPassword =  password_hash($_POST["password"],PASSWORD_DEFAULT);
                    echo json_encode(UserModel::createNewUser($fname, $mname, $lname, $mobile, $email, $hashPassword, 0));
                    break;
            } 
            break;
        case "PATCH":
            break;
        case "DELETE":
            break;
    
    }
}
catch(Exception $e){
    http_response_code($e->getCode());
    echo json_encode(Array("message"=>$e->getMessage()));

}

?>