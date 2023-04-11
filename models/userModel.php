<?php
// require_once('dbConnection.php');
require_once('./models/dbConnection.php');

class UserModel{
    protected $con = null;
    function __construct() {
        $db = new DbConnection();
        $this->con =$db->getInstance();
    }
    function getUserProfileByPhone($mobile){
        $conn = DbConnection::getInstance();
        $stmt = $conn->prepare('SELECT * FROM user WHERE mobile = ?');
        $stmt->bind_param('s', $mobile); 
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $newUser = array("id" => $row["id"],"name" => $row["name"],"mobile" => $row["mobile"], 
                "email" => $row["email"], "isAdmin" => $row["isAdmin"], "avatar" => $row["avatar"]);
        }
        return $newUser;
    }
    function getUserProfileById($id){
        $conn = DbConnection::getInstance();
        $stmt = $conn->prepare('SELECT * FROM user WHERE id = ?');
        $stmt->bind_param('s', $id); 
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $newUser = array("id" => $row["id"],"name" => $row["name"],"mobile" => $row["mobile"], 
                "email" => $row["email"], "isAdmin" => $row["isAdmin"], "avatar" => $row["avatar"]);
        }
        return $newUser;
    }
    function checkUserExistence($phone){
        $conn = DbConnection::getInstance();
        $stmt = $conn->prepare('SELECT * FROM `user` WHERE mobile = ?');
        $stmt->bind_param('s', $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = array();
        while($row = $result->fetch_assoc()){
            array_push($user, array("id" => $row["id"]));
        }
        return count($user);
    }
    function createNewUser($name, $mobile, $email, $password, $isAdmin){
        $conn = DbConnection::getInstance();
        $stmt = $conn->prepare("INSERT INTO user(name, mobile, email, hashedPassword, registeredAt, lastLogin, passwordChangedAt,isAdmin,avatar) 
                VALUES (?, ?, ?, ?, ?, ?, CURRENT_DATE, CURRENT_DATE, CURRENT_DATE, ?, '')");
        $stmt->bind_param('ssssi', $name, $mobile, $email, $password, $isAdmin); // 's' specifies the variable type => 'string'
        $stmt->execute(); 
        $newUser = self::getUserProfileByPhone($mobile);
        return $newUser;
    }
    
    function getAvatarFileName($id){
        $conn = DbConnection::getInstance();
        $stmt = $conn->prepare('SELECT avatar FROM user WHERE id = ?');
        $stmt->bind_param('s', $id); 
        $stmt->execute(); 
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row["avatar"];
    }      

    function updateAvatar($fileName, $phone){
        $conn = DbConnection::getInstance();
        $stmt = $conn->prepare("update user set avatar = ? where mobile = ?");
        $stmt->bind_param('ss', $fileName, $phone); // 's' specifies the variable type => 'string'
        $stmt->execute();
    }         
    
    function comparePassword($phone,$password){
        $conn = DbConnection::getInstance();
        $stmt = $conn->prepare('SELECT hashedPassword FROM user WHERE mobile = ?');
        $stmt->bind_param('s', $phone); // 's' specifies the variable type => 'string'
        $stmt->execute();
        $result = $stmt->get_result(); 
        $row = $result->fetch_assoc();            
        return password_verify($password, $row["hashedPassword"]);   
    }
    function updatePassword($phone,$password){
        $conn = DbConnection::getInstance();
        $stmt = $conn->prepare('update user set hashedPassword = ? where mobile = ?');
        $stmt->bind_param('ss',$password, $phone); // 's' specifies the variable type => 'string'
        $stmt->execute();
        return ["message" => "Update successful"]; 
    }
    function editProfile($name, $mobile, $email, $id){
        $conn = DbConnection::getInstance();
        $stmt = $conn->prepare("UPDATE  user set name = ?, mobile = ?, email = ?
                where id= ?");
        $stmt->bind_param('sssi',$name, $mobile, $email, $id); // 's' specifies the variable type => 'string'
        $stmt->execute(); 
        $newUser = self::getUserProfileByPhone($mobile);
        return $newUser;

    }
}

?>