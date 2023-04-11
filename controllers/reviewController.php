<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../middlewares/auth.php";
use Firebase\JWT\JWT;
class reviewController extends Controller
{
    protected $conn = null;
    function __construct() {
        $conn = $this->model("reviewModel");
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