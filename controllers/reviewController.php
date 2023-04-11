<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../middlewares/auth.php";
use Firebase\JWT\JWT;
class reviewController extends Controller
{
    function getReviewByProductId($productId){
        try{
            $conn = $this->model("reviewModel");
            $productId = $productId[0];
            if($productId == ':productId')
                throw new Exception('Invalid product!', 400);
            $result =  $conn->getReviewByProductId($productId);                
            echo json_encode($result);
        }
        catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(Array("msg"=>$e->getMessage()));
        }
    }
    function addReview(){
        try{
            parse_str(file_get_contents('php://input'),$data);
            $con = $this->model("reviewModel");
            $productId = $data['productId'];
            $userId = $data['userId'];
            $star = $data['star'];
            $content = $data['content'];
            if ($star < 1 || $star > 5)
                throw new Exception('Invalid star!', 400);
            $result = $con->addReview($productId, $userId, $star, $content);
            echo json_encode(["msg"=> "Add Review Success"]);
        }
        catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(Array("msg"=>$e->getMessage()));
        }
    }
    function editReview($id){
        
    }
    function deleteReview($reviewId){
        try{
            $conn = $this->model("reviewModel");
            $reviewId = $reviewId[0];
            if($reviewId == ':reviewId')
                throw new Exception('Invalid review!', 400);
            $conn->deleteReview($reviewId);
            echo json_encode(["msg"=> "Delete Review Success"]);

        }
        catch(Exception $e){
            http_response_code($e->getCode());
            echo json_encode(Array("msg"=>$e->getMessage()));
        }

    }

}
?>