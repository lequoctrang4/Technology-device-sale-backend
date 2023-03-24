<?php
    require_once('dbConnection.php');
class UserModel{

public static function checkUserExistence($phone){
            $conn = DbConnection::getInstance();
            
        }
        public static function createNewUser($firstName, $middleName, $lastName, $mobile, $email, $password, $isAdmin){
            $conn = DbConnection::getInstance();
            
        }
        public static function getUserProfile($phone){
            $conn = DbConnection::getInstance();
            
        }
        
    }
?>