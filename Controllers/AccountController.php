<?php

declare(strict_types=1);

namespace Main\Controllers;

use Sabre\HTTP\Request;
use Sabre\HTTP\Response;
use Exception;
use Main\Models\UserModel;
use DateTimeImmutable;
use Firebase\JWT\JWT;

require_once __DIR__ . "/../Utils/SqlUtils.php";


class AccountController
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

    function signIn()
    {
        try {
            // parse_str(file_get_contents('php://input'), $data);
            $data = json_decode($this->request->getBodyAsString());
            // $con = $this->model("userModel");
            $mobile = $data->mobile;
            $password = $data->password;
            [$status, $err] = $this->model->checkUserExistence($mobile);
            if (!$status) {

                $this->response->setBody("User has not signed up yet!");
                $this->response->setStatus(400);
                return;
            }
            [$status, $err] = $this->model->comparePassword($mobile, $password);
            if (!$status) {
                $this->response->setStatus(400);
                $this->response->setBody($err);
                return;
            }
            [$status, $err] = $this->model->getUserProfileByPhone($mobile);
            $key = "privatekey";
            if ($status) {
                $user = json_decode($err)[0];
                $date = new DateTimeImmutable();
                $expire_at = $date->modify('+5 days')->getTimestamp();
                $payload = array("id" => $user->id, "mobile" => $user->mobile, "email" => $user->email, "isAdmin" => $user->isAdmin, "expire_at" => $expire_at);
                $jwt = JWT::encode($payload, $key, 'HS256');
                // $jwt = '';
                $user = [
                    "token" => $jwt
                ];
                $this->response->setStatus(200);
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setBody(json_encode($user));
                return;
            }
            $this->response->setStatus(200);
            $this->response->setBody('bug');
            return;
        } catch (Exception $e) {
            http_response_code($e->getCode());
            $this->response->setStatus($e->getCode());
            $this->response->setBody(json_encode(array("msg" => $e->getMessage())));
        }
    }
    function signUp()
    {
        try {
            // parse_str(file_get_contents('php://input'), $data);
            $data = json_decode($this->request->getBodyAsString());
            if ($data->name == '')
                throw new Exception("Bạn cần phải nhập đầy đủ họ và tên!", 400);
            if (!preg_match('/^[0-9]{10}+$/', $data->mobile))
                throw new Exception("Bạn cần phải nhập số điện thoại!", 400);
            if (!filter_var($data->email, FILTER_VALIDATE_EMAIL))
                throw new Exception("Định dạng Email không đúng", 400);
            if (strlen($data->password) < 10)
                throw new Exception("Độ dài mật khẩu ít nhất là 10!", 400);
            if ($data->password != $data->confirmPassword)
                throw new Exception("Nhập lại mật khẩu không đúng!", 400);
            // $fname = $data->firstName;
            // $mname = $data->middleName;
            // $lname = $data->lastName;
            $fname = "hello";
            $mname = "mid";
            $lname = "last";

            $mobile = $data->mobile;
            $email = $data->email;
            $password = $data->password;
            [$status, $err] = $this->model->checkUserExistence($mobile);
            if ($status) {
                throw new Exception("User existed: ", 400);
            }
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);

            [$status, $err] = $this->model->createNewUser($fname, $mname, $lname, $mobile, $email, $hashPassword, 0);
            if ($status) {
                $this->response->setStatus(200);
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setBody($err);
                return;
            } else {
                $this->response->setStatus(400);
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setBody($err);
                return;
            }
            $this->response->setStatus(400);
            $this->response->setBody('bug');
            return;
        } catch (Exception $e) {
            $this->response->setStatus(400);
            $this->response->setBody($e->getMessage());
            return;
            // echo json_encode(array("msg" => $e->getMessage()));
        }
        echo "hello";
    }
}
