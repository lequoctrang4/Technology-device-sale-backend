<?php
require_once('./models/dbConnection.php');

class ReviewModel{
    protected $conn = null;
    function __construct() {
        $db = new DbConnection();
        $this->conn =$db->getInstance();
    }
    function getReviewByProductId($product_id){
        
    }
    function addReview(){

    }
    function editReview($id){

    }
    function deleteReview($id){

    }
}

?>