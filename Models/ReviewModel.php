<?php

namespace Main\Models;

class ReviewModel
{
    protected $con = null;
    function __construct()
    {
        $db = new DbConnection();
        $this->con = $db->getInstance();
    }
    function getReviewById($id)
    {
        $qr = "SELECT * FROM review WHERE id = \"$id\"";
        $res = $this->con->query($qr);
        if ($res) {
            $res =  get_array_from_result($res);
            return [true, json_encode($res)];
        } else {
            return [false, json_encode(['msg' => 'Error when fetch product'])];
        }
    }
    function getReviewByProductId($productId)
    {
        $qr = "SELECT * FROM review WHERE productId = \"$productId\"";
        $res = $this->con->query($qr);
        if ($res) {
            $res =  get_array_from_result($res);
            return [true, json_encode($res)];
        } else {
            return [False, json_encode(['msg' => 'Error when fetch product'])];
        }
    }
    function addReview($review)
    {
        $qr = "insert into review
        (productId,
        userId,
        star, content)
        values
        ($review->productId,
        $review->userId,
        $review->star,
        \"$review->content\");";
        $res = $this->con->query($qr);
        if ($res) {
            return [true, json_encode(['msg' => 'insert success'])];
        } else {
            return [false, json_encode(["msg" => "failed"])];
        }
    }
    function editReview($params)
    {
        $id = $params->Id;
        [$status, $err] = $this->getReviewByProductId(($id));
        if ($status) {
            $qrParams = array();
            if (isset($params->content)) {
                $qrParams[] =  "content=\"$params->content\"";
            }
            if (isset($params->color)) {
                $qrParams[] = "star=\"$params->star\"";
            }
            $qr = "update `product` set " . implode(',', $qrParams) . " where id = $id";
            $res = $this->con->query($qr);
            if ($res) {
                return [true, json_encode(['msg' => 'success edit review'])];
            } else {
                return [false, json_encode(['msg' => 'failed edit review'])];
            }
        } else {
            return [$status, $err];
        }
    }
    function deleteReview($id)
    {
        $qr = "DELETE FROM review WHERE id = $id";
        $res = $this->con->query($qr);
        if ($res) {
            return [true, json_encode(["msg" => "Delete Review Success"])];
        } else {
            return [false, json_encode(["msg" => "failed"])];
        }
    }
}
