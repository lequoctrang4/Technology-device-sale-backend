<?php
    require_once('./models/dbConnection.php');
class ProductModel{

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