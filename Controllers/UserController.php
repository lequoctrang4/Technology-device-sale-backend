<?php

namespace Main\Controllers;

use Sabre\HTTP\Request;
use Sabre\HTTP\Response;
use Main\Models\UserModel;
use Exception;

require_once __DIR__ . "/../Utils/SqlUtils.php";

class UserController
{
    private $request;
    private $response;
    private $model;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->model = new UserModel();
    }
    function getProfile()
    {
        // echo "hello";
        // $con = $this->model("userModel");
        // if (!isset(apache_request_headers()["Authorization"]) || !preg_match('/Bearer\s(\S+)/', apache_request_headers()["Authorization"], $matches))
        //     throw new Exception("Cannot find token!", 400);
        // $user = authenticate($matches[1]);
        $user = json_decode($this->request->getHeader('User-info'));
        [$status, $err] = $this->model->getUserProfileByPhone($user->mobile);
        if ($status) {
            $this->response->setStatus(200);
        } else {
            $this->response->setStatus(500);
        }
        $this->response->setBody($err);
        $this->response->setHeader('Content-type', 'application/json');

        // echo json_encode($result);
    }

    function setAvatar()
    {
        try {
            // parse_str(file_get_contents('php://input'),$data);
            $avatar = $_FILES["avatar"]["name"];
            $extension = pathinfo($avatar)["extension"];
            if (mb_strtolower($extension) != "png" && mb_strtolower($extension) != "jpg" && mb_strtolower($extension) != "jpeg") {
                throw new Exception("We only allow png or jpg files!", 400);
            }
            $tempname = $_FILES["avatar"]["tmp_name"];
            $user = $this->request->getHeader('User-info');
            $avatar = $user["mobile"] . "." . $extension;
            $folder = __DIR__ . "/../images/user/" . $avatar;
            if (!move_uploaded_file($tempname, $folder)) {
                throw new Exception("Fail to change avatar!", 500);
            }
            $this->model->updateAvatar($avatar, $user["mobile"]);
            $this->response->setStatus(200);
            $this->response->setBody(json_encode(["message" => "Uploaded image sucessfully!"]));
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msg" => $e->getMessage()));
        }
    }
    function getAvatar()
    {
        $user = json_decode($this->request->getHeader('User-info'));
        try {
            [$status, $err] = $this->model->getAvatarFileName($user->id);
            if ($status) {
                var_dump($err);
                // $name = __DIR__ . "/../images/user/$fileName";
                // $type = pathinfo($name, PATHINFO_EXTENSION);
                // $data = file_get_contents($name);
                // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $this->response->setStatus(200);
                // $this->response->setBody(json_encode(array("avatar" => $base64)));
                return;
            }
        } catch (Exception $e) {
            $this->response->setStatus($e->getCode());
            $this->response->setBody(json_encode(array("msg" => $e->getMessage())));
        }
    }
    function editProfile()
    {
        try {
            $data = $this->request->getBody();
            $user = json_decode($this->request->getHeader('User-info'));
            if ($data['firstName'] == '' || $data['middleName'] == '' || $data['lastName'] == '')
                throw new Exception("Bạn cần phải nhập đầy đủ họ và tên!", 400);
            if (!preg_match('/^[0-9]{10}+$/', $data['mobile']))
                throw new Exception("Bạn cần phải nhập đúng số điện thoại!", 400);
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
                throw new Exception("Định dạng Email không đúng", 400);
            $fname = $data["firstName"];
            $mname = $data["middleName"];
            $lname = $data["lastName"];
            $mobile = $data["mobile"];
            $email = $data["email"];
            [$status, $err] = $this->model->checkUserExistence($mobile);
            if ($status)
                throw new Exception("Số điện thoại đã tồn tại", 400);
            [$status, $err] = $this->model->editProfile($fname, $mname, $lname, $mobile, $email, $user["id"]);
            if ($status) {
                $this->response->setStatus(200);
                $this->response->setBody($err);
            } else {
                throw new Exception("Can't edit profile", 500);
            }
        } catch (Exception $e) {
            $this->response->setStatus($e->getCode());
            $this->response->setBody(json_encode(array("msg" => $e->getMessage())));
        }
    }
    function changePassword()
    {
        try {
            $data = $this->request->getBody();
            $user = json_decode($this->request->getHeader('User-info'));
            $oldPassword = $data["oldPassword"];
            $newPassword = $data["newPassword"];
            $confirmPassword = $data["confirmPassword"];
            if (strlen($oldPassword) == 0 || strlen($newPassword) == 0 || strlen($confirmPassword) == 0)
                throw new Exception("Vui lòng nhập đủ tất cả các trường!", 400);
            if (!$this->model->comparePassword($user['mobile'], $oldPassword))
                throw new Exception("Mật khẩu cũ không đúng!", 400);
            if (strlen($newPassword) < 10)
                throw new Exception("Độ dài mật khẩu ít nhất là 10!", 400);
            if ($newPassword == $oldPassword)
                throw new Exception("Mật khẩu cũ phải khác mật khẩu mới!", 400);
            if ($newPassword != $confirmPassword)
                throw new Exception("Mật khẩu nhập lại không khớp!", 400);
            $hashPassword =  password_hash($newPassword, PASSWORD_DEFAULT);
            [$status, $err] = $this->model->updatePassword($user["mobile"], $hashPassword);
            if ($status) {
                $this->response->setStatus(200);
                $this->response->setBody($err);
            } else {
                throw new Exception($err, 500);
            }
        } catch (Exception $e) {
            $this->response->setStatus($e->getCode());
            $this->response->setBody(json_encode(array("msg" => $e->getMessage())));
        }
    }
    function forgetPassword()
    {
        try {
            $this->response->setBody(json_encode("Chưa làm!"));
        } catch (Exception $e) {
            $this->response->setStatus($e->getCode());
            $this->response->setBody(json_encode(array("msg" => $e->getMessage())));
        }
    }
    function getUsers()
    {
        [$status, $err] = $this->model->getAllUsers();
        if ($status) {
            $this->response->setStatus(200);
            $this->response->setHeader('Content-type', 'application/json');
            $this->response->setBody($err);
        } else {
            $this->response->setStatus(404);
            $this->response->setBody($err);
        }
    }
}
