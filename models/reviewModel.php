<?php
require_once('./models/dbConnection.php');

class reviewModel{
    protected $conn = null;
    function __construct() {
        $db = new DbConnection();
        $this->conn =$db->getInstance();
    }
    function getReviewByProductId($productId){
        $stmt = $this->conn->prepare('SELECT * FROM review WHERE productId = ?');
        $stmt->bind_param('i', $productId); 
        $stmt->execute();
        $result = $stmt->get_result();
        $review = [];
        while($row = $result->fetch_assoc()){
            array_push($review, $row);
        }
        return $review;
    }
    function addReview($productId, $userId, $star, $content){
        $stmt = $this->conn->prepare('INSERT INTO review VALUES (NULL,?,?,?, CURRENT_TIME, ?)');
        $stmt->bind_param('iiis', $productId, $userId, $star, $content); 
        $stmt->execute();
        return True;
    }
    function editReview($id){

    }
    function deleteReview($id){
        $stmt = $this->conn->prepare('DELETE FROM review WHERE id = ?');
        $stmt->bind_param('i', $id); 
        $stmt->execute();
        return True;
    }
}

?>