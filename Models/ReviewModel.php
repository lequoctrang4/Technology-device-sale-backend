<?php

namespace Main\Models;

use Exception;

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
            return [false, json_encode(['msg' => 'Error when fetch product'])];
        }
    }
    function addReview($review)
    {
        $qr = "INSERT INTO review(
            productId,
            userId,
            star,
            content
            )
            VALUES($review->productId,$review->userId,$review->star,\"$review->content\")";
        try {
            $res = $this->con->query($qr);

            if ($res) {
                return [true, json_encode(['msg' => 'insert success'])];
            } else {
                return [false, json_encode(['msg' => 'internal error'])];
            }
        } catch (Exception $e) {
            return [false, json_encode(['msg' => $e->getMessage()])];
        }
    }
    function editReview($params)
    {
        $id = $params->reviewId;
        [$status, $err] = $this->getReviewByProductId($id);
        if ($status) {
            $qrParams = array();
            if (isset($params->content)) {
                $qrParams[] =  "content=\"$params->content\"";
            }
            if (isset($params->color)) {
                $qrParams[] = "star=\"$params->star\"";
            }
            $qr = "update `review` set " . implode(',', $qrParams) . " where id = $id";
            try {
                $res = $this->con->query($qr);
                if ($res) {
                    return [true, json_encode(['msg' => 'success edit review'])];
                } else {
                    return [false, json_encode(['msg' => 'failed edit review'])];
                }
            } catch (Exception $e) {
                return [false, json_encode(['msg' => $e->getMessage()])];
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
