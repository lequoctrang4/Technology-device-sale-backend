<?php
// require_once('dbConnection.php');
namespace Main\Models;

use Main\Models\DbConnection;

require_once "./Utils/SqlUtils.php";
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
    function createNewUser($firstName, $middleName, $lastName, $mobile, $email, $password, $isAdmin)
    {
        $qr = "INSERT INTO user(
            firstName,
            middleName,
            lastName,
            mobile,
            email,
            hashedPassword,
            registeredAt,
            lastLogin,
            passwordChangedAt,
            isAdmin,
            avatar
            ) 
            VALUES (
                \"$firstName\",
                \"$middleName\",
                \"$lastName\",
                \"$mobile\",
                \"$email\",
                \"$password\",
                CURRENT_DATE,
                CURRENT_DATE,
                CURRENT_DATE,
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
        $stmt = $this->con->prepare("update user set avatar = ? where mobile = ?");
        $stmt->bind_param('ss', $fileName, $phone); // 's' specifies the variable type => 'string'
        $stmt->execute();
        $res = $stmt->get_result();
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
    function editProfile($firstName, $middleName, $lastName, $mobile, $email, $id)
    {
        $stmt = $this->con->prepare("UPDATE  user set firstName = ?, middleName = ?, lastName = ?, mobile = ?, email = ?
                where id= ?");
        $stmt->bind_param('sssssi', $firstName, $middleName, $lastName, $mobile, $email, $id); // 's' specifies the variable type => 'string'
        $stmt->execute();
        return $this->getUserProfileByPhone($mobile);
    }
}
