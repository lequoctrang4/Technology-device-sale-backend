<?php
// namespace model;
namespace Main\Models;

use Main\Models\DbConnection;
use Main\Models\AttributeModel;
use  Main\Utils\SqlUtils;

require_once __DIR__ . '/../Utils/SqlUtils.php';
class ProductModel
{
    protected $con = null;
    function __construct()
    {
        $db = new DbConnection();
        $this->con = $db->getInstance();
    }
    function getAllProducts()
    {
        // echo "getAllProducts models";
        $qr = "SELECT * FROM `product`";
        $res = $this->con->query($qr);
        $res =  get_array_from_result($res);
        if (!empty($res)) {
            return [true, json_encode($res)];
        } else {
            return [False, 'Error when fetch all products '];
        }
        return [True, $res];
    }
    function getProductsByCategory($id)
    {
        $qr = "
        SELECT * 
        FROM `product`
        where id in (
            select productID
            from `product_category`
            where categoryID =$id
            )";
        $res = $this->con->query($qr);
        if ($res) {
            $res =  get_array_from_result($res);
            return [true, json_encode($res)];
        } else {
            return [False, 'Error when fetch all products '];
        }
        return [True, $res];
    }
    function getProductById($id)
    {
        $qr = "SELECT * FROM `product` where id = $id";
        $res = $this->con->query($qr);
        $res =  get_array_from_result($res);
        if (!empty($res)) {
            return [true, json_encode($res)];
        } else {
            return [true, json_encode(["Not found product with id: $id"])];
        }
        return [true, json_encode($res)];
    }
    function getProductByName($name)
    {
        $qr = "SELECT * FROM `product` where name = $name";
        $res = $this->con->query($qr);
        $res =  get_array_from_result($res);
        if (!empty($res)) {
            return [true, json_encode($res)];
        } else {
            return [true, json_encode(["Not found product with name: $name"])];
        }
        return [true, json_encode($res)];
    }
    function createProduct($params)
    {
        $qr = "insert into product (name, color, salePercent, price, manufacturer, html) values (\"$params->name\", \"$params->color\", \"$params->salePercent\", \"$params->price\", \"$params->manufacturer\", \"$params->html\");";
        $res = $this->con->query($qr);
        $last_id = $this->con->query("SELECT id FROM product ORDER BY id DESC LIMIT 1;");
        $last_id = get_array_from_result($last_id)[0]["id"];
        foreach ($params->attr as $key => $value) {
            $att_qr = "select id from attributes where name=\"$key\"";
            // echo "$key =>  $value";
            $res = $this->con->query($att_qr);
            // var_dump($id);
            if (($res->num_rows > 0)) {
                $att_id = get_array_from_result($res)[0]["id"];
            } else {
                continue;
            }
            $att_qr = "insert into attribute_value (`id_attribute`, `productID`, `value`) values ($att_id, $last_id, $value)";
            if ($this->con->query($att_qr)) {
                // echo "success";
            } else {
                echo "error in row" . $att_qr;
            }
        }
        return $this->getProductById($last_id);
    }
    function updateProduct($params)
    {
        $id = $params->Id;
        [$status, $err] = $this->getProductById(($id));
        if ($status) {
            $qrParams = array();
            if (isset($params->name)) {
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
            $qr = "update `product` set " . implode(',', $qrParams) . " where id = $id";
            $res = $this->con->query($qr);
            if ($res) {
                return [true, ''];
            } else {
                return [false, 'Failed'];
            }
        } else {
            return [$status, $err];
        }
    }
    function deleteProduct($id)
    {
        [$status, $err] = $this->getProductById($id);
        if ($status) {
            $qr = "delete from `product` where id = $id";
            $res = $this->con->query($qr);
            if ($res) {
                return [true, ''];
            } else {
                return [false, 'Failed'];
            }
        } else {
            // echo "not found";
            return [$status, $err];
        }
    }
    function getCategories()
    {
        $qr = "SELECT * FROM `category`";
        $res = $this->con->query($qr);
        if ($res) {
            $res =  get_array_from_result($res);
            return [true, json_encode($res)];
        } else {
            return [False, 'Error when fetch all products '];
        }
        return [True, $res];
    }
    function getProductAttributes($id)
    {
        $qr = "SELECT group_name, name, value FROM `attribute_value` INNER JOIN `attributes` ON attribute_value.id_attribute = attributes.id where `productId` = $id ORDER BY group_name";
        $res = $this->con->query($qr);
        if ($res) {
            $res =  get_array_from_result($res);
            return [true, json_encode($res)];
        } else {
            return [False, 'Error when fetch all Attributes'];
        }
        return [True, $res];
    }
    function getProductByManufacturer($name)
    {
        $qr = "SELECT * FROM `product` where LOWER(manufacturer) = LOWER(\"$name\")";
        $res = $this->con->query($qr);
        if ($res) {
            $res =  get_array_from_result($res);
            return [true, json_encode($res)];
        } else {
            return [False, 'Error when fetch all product'];
        }
        return [True, $res];
    }
}
