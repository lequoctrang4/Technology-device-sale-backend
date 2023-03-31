<?php
// require_once('dbConnection.php');
require_once('./models/dbConnection.php');

class UserModel{
    protected $con = null;
    function __construct() {
        $db = new DbConnection();
        $this->con =$db->getInstance();
    }
    function getUserProfile($mobile){
        $conn = DbConnection::getInstance();
        $stmt = $conn->prepare('SELECT * FROM user WHERE mobile = ?');
        $stmt->bind_param('s', $mobile); 
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $newUser = array("id" => $row["id"],"firstName" => $row["firstName"],"middleName" => $row["middleName"],
                "lastName" => $row["lastName"],"mobile" => $row["mobile"], 
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
    function createNewUser($firstName, $middleName, $lastName, $mobile, $email, $password, $isAdmin){
        $conn = DbConnection::getInstance();
         $stmt = $conn->prepare("INSERT INTO user(firstName, middleName, lastName, mobile, email, hashedPassword, registeredAt, lastLogin, passwordChangedAt,isAdmin,avatar) 
                VALUES (?, ?, ?, ?, ?, ?, CURRENT_DATE, CURRENT_DATE, CURRENT_DATE, ?, '')");
        $stmt->bind_param('ssssssi',$firstName, $middleName, $lastName, $mobile, $email, $password, $isAdmin); // 's' specifies the variable type => 'string'
        $stmt->execute(); 
        $newUser = self::getUserProfile($mobile);
        return $newUser;
    }
    
    function getAvatarFileName($phone){
        $conn = DbConnection::getInstance();
    }      

    function updateAvatarName($phone,$fileName){
        $conn = DbConnection::getInstance();
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
    }
    function editProfile($firstName, $middleName, $lastName, $mobile, $email,){
        $conn = DbConnection::getInstance();   
    }
    function getAllUsers(){
        $conn = DbConnection::getInstance();
    }
}

?>