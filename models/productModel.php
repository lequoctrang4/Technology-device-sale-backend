<?php
    require_once('./models/dbConnection.php');
    class ProductModel {
        protected $con = null;
        function __construct() {
            $db = new DbConnection();
            $this->con =$db->getInstance();
        }
        function getAllProducts() {
            $qr = "SELECT * FROM `product`";
            $res = $this->con->query($qr);
            return $res;
         }
        function getProductById($id) {
            $qr = "SELECT * FROM `product` where id = $id";
            $res = $this->con->query($qr);
            return $res;
        }
        function createProduct($params) {
            $qr = "insert into product (name, color, salePercent, price, manufacturer) values (\"$params->name\", \"$params->color\", \"$params->salePercent\", \"$params->price\", \"$params->manufacturer\");";
            $res = $this->con->query($qr);
            if (!$res) {
                echo "404";
            } else {
                echo "success";
            }
        }
        function updateProduct($params) {
            $id = $params->Id;
            if ($this->getProductById($id)) {
                $qrParams = array();
                if (isset($params ->name)) {
                    $qrParams[] =  "name=\"$params->name\"";
                }
                if (isset($params->color)) {
                    $qrParams[] = "color=\"$params->color\"";
                }
                if (isset($params->salePercent)) {
                    $qrParams[] = "salePercent=\"$params->salePercent\"";
                }
                if (isset($params->price)) {
                    $qrParams[] = "price=\"$params->price\"";
                }
                if (isset($params->manufacturer)) {
                    $qrParams[] = "manufacturer=\"$params->manufacturer\"";
                }
                if (isset($params->html)) {
                    $qrParams[] = "html=\"$params->html\"";
                }
                $qr = "update `product` set ".implode(',', $qrParams)." where id = $id";
                $res = $this->con->query($qr);
                if (!$res) {
                    echo"404";
                }
                else {
                    echo "success";
                }
            } else {
                echo "not found";
            }
        }
        function deleteProduct($id) {
            $qr = "delete from `product` where id = $id";
            $res = $this->con->query($qr);
            if ($res) {
                echo "Success";
            } else {
                echo "404";
            }
        }
    }
?>