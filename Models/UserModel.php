<?php
// require_once('dbConnection.php');
namespace Main\Models;

use Exception;
use Main\Models\DbConnection;

require_once __DIR__ . "/../Utils/JWTUtils.php";
require_once  __DIR__ . "/../Utils/SqlUtils.php";
class UserModel
{
    protected $con = null;
    function __construct()
    {
        $db = new DbConnection();
        $this->con = $db->getInstance();
    }
    function getAllUsers()
    {
        $qr = "SELECT * FROM `user`";
        $res = $this->con->query($qr);
        $res =  get_array_from_result($res);
        if (!empty($res)) {
            return [true, json_encode($res)];
        } else {
            return [False, 'Error when fetch all products '];
        }
        return [True, $res];
    }
    function getUserProfileByPhone($mobile)
    {
        $qr = "SELECT * FROM user WHERE mobile = \"$mobile\"";
        // $stmt->bind_param('s', $mobile);
        $res = $this->con->query($qr);
        $res =  get_array_from_result($res);

        // if ($row = $result->fetch_assoc()) {
        //     $newUser = array(
        //         "id" => $row["id"],
        //         "firstName" => $row["firstName"],
        //         "middleName" => $row["middleName"],
        //         "lastName" => $row["lastName"],
        //         "mobile" => $row["mobile"],
        //         "email" => $row["email"],
        //         "isAdmin" => $row["isAdmin"],
        //         "avatar" => $row["avatar"]
        //     );
        // }
        if (!empty($res)) {
            // echo "helo";
            // var_dump(json_encode($res));
            return [true, json_encode($res)];
        } else {
            return [false, json_encode(["Not found user with mobile: $mobile"])];
        }
        return [true, json_encode($res)];
    }
    function getUserProfileById($id)
    {
        $qr = "SELECT * FROM user WHERE id = \"$id\"";

        $res = $this->con->query($qr);
        $res =  get_array_from_result($res);

        if (!empty($res)) {
            return [true, json_encode($res)];
        } else {
            return [false, json_encode(["Not found user with id: $id"])];
        }
        return [true, json_encode($res)];
    }
    function getUserProfileByPhoneAndEmail($phone, $email) {
        $qr = "SELECT * FROM user WHERE mobile = \"$phone\" and email = \"$email\"";

        $res = $this->con->query($qr);
        $res =  get_array_from_result($res);

        if (!empty($res)) {
            return [true, json_encode($res)];
        } else {
            return [false, json_encode(["Not found user with id"])];
        }
        return [true, json_encode($res)];
    }
    function checkUserExistence($phone)
    {
        $qr = "SELECT * FROM `user` WHERE mobile =\"$phone\"";
        $res = $this->con->query($qr);
        $res =  get_array_from_result($res);

        // $user = array();
        // while ($row = $result->fetch_assoc()) {
        //     array_push($user, array("id" => $row["id"]));
        // }
        if (!empty($res)) {
            return [true, json_encode($res)];
        } else {
            return [false, json_encode(["Not found user with mobile: $phone"])];
        }
        return [true, json_encode($res)];
    }
    function createNewUser($name, $mobile, $email, $password, $isAdmin)
    {
        $qr = "INSERT INTO user(
            name,
            mobile,
            email,
            hashedPassword,
            isAdmin,
            avatar
            ) 
            VALUES (
                \"$name\",
                \"$mobile\",
                \"$email\",
                \"$password\",
                $isAdmin,
                ''
                )";

        // echo $qr;
        // return;
        $res = $this->con->query($qr);
        // $stmt->bind_param('ssssssi', $firstName, $middleName, $lastName, $mobile, $email, $password, $isAdmin); // 's' specifies the variable type => 'string'
        // $stmt->execute();


        // $res =  get_array_from_result($res);
        // $res =  get_array_from_result($res);
        if (!$res) {
            return [false, json_encode(["Can't create new user"])];
        }
        return $this->getUserProfileByPhone($mobile);
    }

    function getAvatarFileName($id)
    {
        $qr = "SELECT avatar FROM user WHERE id = $id";
        // $stmt->bind_param('s', $id);
        // $stmt->execute();
        $result = $this->con->query($qr);
        $res =  get_array_from_result($result);
        if (!empty($res)) {
            return [true, json_encode($res)];
        } else {
            return [false, json_encode(["Not found user with id: $id"])];
        }
        return [true, json_encode($res)];
        // return $row["avatar"];
    }

    function updateAvatar($fileName, $phone)
    {
        $qr = "update user set avatar = \"$fileName\" where mobile = \"$phone\"";
        // $stmt->bind_param('ss', $fileName, $phone); // 's' specifies the variable type => 'string'
        $res = $this->con->query($qr);
        if ($res) {
            return $this->getUserProfileByPhone($phone);
        } else {
            return [false, "Can\'t update avatar for user with phone: $phone"];
        }
    }

    function comparePassword($phone, $password)
    {
        $qr = "SELECT hashedPassword FROM user WHERE mobile = \"$phone\"";
        // $stmt->bind_param('s', $phone); // 's' specifies the variable type => 'string'
        // $stmt->execute();
        $result = $this->con->query($qr);
        if ($result) {
            $res =  get_array_from_result($result);
            $res = json_decode(json_encode($res[0]));
            $hashedPassword = $res->hashedPassword;
            if (password_verify($password, $hashedPassword)) {
                return [true, ""];
            } else {
                return [false, "password not correct"];
            }
        } else {
            return [false, json_encode(["Not found user with phone: $phone"])];
        }
        // return [true, json_encode($res)];
    }
    function updatePassword($phone, $password)
    {
        $stmt = $this->con->prepare('update user set hashedPassword = ? where mobile = ?');
        $stmt->bind_param('ss', $password, $phone); // 's' specifies the variable type => 'string'
        $stmt->execute();
        return $this->getUserProfileByPhone($phone);
        $result = $stmt->get_result();
        if ($result) {
            return $this->getUserProfileByPhone($phone);
        } else {
            return [false, "Can\'t update password for user with phone: $phone"];
        }
    }
    function editProfile($params, $id)
    {
        $qrParams = [];
        foreach ($params as $key => $value) {
            $qrParams[] =  "$key=\"$value\"";
        };
        $qr = "UPDATE  user set " . implode(',', $qrParams) . " where id= $id";
        try {
            $res = $this->con->query($qr);
            if ($res) {
                [$status, $user] = $this->getUserProfileById($id);
                $key = "privatekey";

                $result = [
                    "token" => getUserToken(json_decode($user)[0], $key)
                ];
                return [true, json_encode($result)];
            } else {
                return [false, json_encode(['msg' => 'failed edit review'])];
            }
        } catch (Exception $e) {
            return [false, json_encode(['msg' => $e->getMessage()])];
        }
        return $this->getUserProfileById($id);
    }
}
