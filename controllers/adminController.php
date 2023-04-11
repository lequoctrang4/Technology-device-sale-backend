<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../middlewares/auth.php";
use Firebase\JWT\JWT;
class adminController extends Controller
{
    function getProfile() 
    {
        $con = $this->model("userModel");
        if (!isset(apache_request_headers()["Authorization"]) || !preg_match('/Bearer\s(\S+)/', apache_request_headers()["Authorization"], $matches))
            throw new Exception("Cannot find token!",400);
        $user = authenticate($matches[1]);
        $result =  $con->getUserProfileByPhone($user["mobile"]);
        echo json_encode($result);
    }
    function signIn(){
        try{
            parse_str(file_get_contents('php://input'),$data);
            $con = $this->model("userModel");
            $mobile = $data["mobile"];
            $password = $data["password"];
            if (!$con->checkUserExistence($mobile)){
                throw new Exception("User has not signed up yet!", 400);
            }
            if (!$con->comparePassword($mobile, $password)){
                throw new Exception("Your password is incorrect!", 400);
            }
            $key = "lequoctrang";
            $user = $con->getUserProfileByPhone($mobile);
            $date = new DateTimeImmutable();
            $expire_at = $date->modify('+5 days')->getTimestamp();
            $payload = Array("id"=>$user["id"], "mobile"=>$user["mobile"],"email"=>$user["email"],"expire_at"=>$expire_at);
            $jwt = JWT::encode($payload, $key, 'HS256');
            $user["token"] = $jwt;
            echo json_encode($user);
        }
        catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(Array("msg"=>$e->getMessage()));
        }

        
    }
    function signUp(){
        try{
            parse_str(file_get_contents('php://input'),$data);
            $con = $this->model("userModel");
            if ($data['firstName'] == '' || $data['middleName'] == '' || $data['lastName'] == '')
                throw new Exception("Bạn cần phải nhập đầy đủ họ và tên!", 400);
            if (! preg_match('/^[0-9]{10}+$/', $data['mobile']))
                throw new Exception("Bạn cần phải nhập số điện thoại!", 400);
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
                throw new Exception("Định dạng Email không đúng", 400);
            if (strlen($data['password']) < 10)
                throw new Exception("Độ dài mật khẩu ít nhất là 10!", 400);
            if ($data['password'] != $data['confirmPassword'])
                throw new Exception("Nhập lại mật khẩu không đúng!", 400);
            $fname = $data["firstName"];
            $mname = $data["middleName"];
            $lname = $data["lastName"];
            $mobile = $data["mobile"];
            $email = $data["email"];
            $password = $data["password"];
            if($con->checkUserExistence($mobile)){
                throw new Exception("Số điện thoại đã tồn tại", 400);
            }
            $hashPassword =  password_hash($password,PASSWORD_DEFAULT);
            echo json_encode($con->createNewUser($fname, $mname, $lname, $mobile, $email, $hashPassword, 0));
        }
        catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(Array("msg"=>$e->getMessage()));
        }
        
    }
    function setAvatar(){
        try{
            $con = $this->model("userModel");
            // parse_str(file_get_contents('php://input'),$data);
            if (!isset(apache_request_headers()["Authorization"]) || ! preg_match('/Bearer\s(\S+)/', apache_request_headers()["Authorization"], $matches)) {
                throw new Exception("Cannot find token!",400);
            }
            $user = authenticate($matches[1]);
            $avatar = $_FILES["avatar"]["name"];
            $extension = pathinfo($avatar)["extension"];
            if (mb_strtolower($extension) != "png" && mb_strtolower($extension) != "jpg" && mb_strtolower($extension) != "jpeg"){
                throw new Exception("We only allow png or jpg files!", 400);
            }
            $tempname = $_FILES["avatar"]["tmp_name"];
            $avatar = $user["mobile"].".".$extension;
            $folder = __DIR__ . "/../images/user/" . $avatar;
            if (!move_uploaded_file($tempname, $folder)) {
                throw new Exception("Fail to change avatar!", 500);
            }
            $con->updateAvatar($avatar, $user["mobile"]);
            echo json_encode(["message" => "Uploaded image sucessfully!"]);
        }
        catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(Array("msg"=>$e->getMessage()));
        }
        
    }
    function getAvatar(){
        try{
            $con = $this->model("userModel");
            if (!isset(apache_request_headers()["Authorization"]) || ! preg_match('/Bearer\s(\S+)/', apache_request_headers()["Authorization"], $matches)) {
                throw new Exception("Cannot find token!",400);
            }
            $user = authenticate($matches[1]);
            $fileName = $con->getAvatarFileName($user['id']);
            $name = __DIR__ . "/../images/user/$fileName";
            $type = pathinfo($name, PATHINFO_EXTENSION);
            $data = file_get_contents($name);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        echo json_encode(array("avatar"=>$base64));
        }
        catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(Array("msg"=>$e->getMessage()));
        }
        
    }
    function editProfile(){
        try{
            $con = $this->model("userModel");
            parse_str(file_get_contents('php://input'),$data);
            if (!isset(apache_request_headers()["Authorization"]) || ! preg_match('/Bearer\s(\S+)/', apache_request_headers()["Authorization"], $matches)) {
                throw new Exception("Cannot find token!",400);
            }
            $user = authenticate($matches[1]);
            if ($data['firstName'] == '' || $data['middleName'] == '' || $data['lastName'] == '')
                throw new Exception("Bạn cần phải nhập đầy đủ họ và tên!", 400);
            if (! preg_match('/^[0-9]{10}+$/', $data['mobile']))
                throw new Exception("Bạn cần phải nhập đúng số điện thoại!", 400);
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
                throw new Exception("Định dạng Email không đúng", 400);
            $fname = $data["firstName"];
            $mname = $data["middleName"];
            $lname = $data["lastName"];
            $mobile = $data["mobile"];
            $email = $data["email"];
            if ($con->checkUserExistence($mobile)) 
                throw new Exception("Số điện thoại đã tồn tại", 400);
            $newUser = $con->editProfile($fname, $mname, $lname, $mobile, $email, $user["id"]);
            echo json_encode($newUser);
        }
        catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(Array("msg"=>$e->getMessage()));
        }
        
        
    }
    function changePassword(){
        try{
            $con = $this->model("userModel");
            parse_str(file_get_contents('php://input'), $data);
            if (!isset(apache_request_headers()["Authorization"]) || ! preg_match('/Bearer\s(\S+)/', apache_request_headers()["Authorization"], $matches)) {
                throw new Exception("Cannot find token!",400);
            }
            $user = authenticate($matches[1]);
            $oldPassword = $data["oldPassword"];
            $newPassword = $data["newPassword"];
            $confirmPassword = $data["confirmPassword"];
            if (strlen($oldPassword) == 0 || strlen($newPassword) == 0 || strlen($confirmPassword) == 0)
                throw new Exception("Vui lòng nhập đủ tất cả các trường!", 400);
            if (!$con->comparePassword($user['mobile'], $oldPassword))
                throw new Exception("Mật khẩu cũ không đúng!", 400);
            if (strlen($newPassword) < 10)
                throw new Exception("Độ dài mật khẩu ít nhất là 10!", 400);
            if ($newPassword == $oldPassword)
                throw new Exception("Mật khẩu cũ phải khác mật khẩu mới!", 400);
            if ($newPassword != $confirmPassword)
                throw new Exception("Mật khẩu nhập lại không khớp!", 400);
            $hashPassword =  password_hash($newPassword,PASSWORD_DEFAULT);

            $mess = $con->updatePassword($user["mobile"], $hashPassword);
            echo json_encode($mess);
        }
        catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(Array("msg"=>$e->getMessage()));
        }
        
    }
    function forgetPassword(){
        try{
            echo json_encode("Chưa làm!");
        }
        catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(Array("msg"=>$e->getMessage()));
        }
        
    }

}
?>