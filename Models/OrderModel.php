<?php

namespace Main\Models;

use Main\Models\DbConnection;
use Main\Models\AttributeModel;
use  Main\Utils\SqlUtils;

require_once __DIR__ . '/../Utils/SqlUtils.php';

class OrderModel
{
    protected $con = null;
    function __construct()
    {
        $db = new DbConnection();
        $this->con = $db->getInstance();
    }
    function getOrderById($id)
    {
        $qr = "select * from `order` where id = $id";
        $order = $this->con->query($qr);
        $order = get_array_from_result($order)[0];
        $qr = "select * from product inner join orderItem on (product.id = orderItem.productId) where orderItem.orderID = $id order by product.id;";
        $products =  $this->con->query($qr);
        $products =  get_array_from_result($products);
        $order["products"] = $products;
        if (!empty($order)) {
            return [true, json_encode($order)];
        } else {
            return [true, json_encode(["Not found order with id: $id"])];
        }
        return [true, json_encode($order)];
    }
    function createOrder($userId, $params)
    {
        $qr = "insert into `order` (
            userId,
            sessionId,
            token,
            status,
            tax,
            subTotal,
            voucherId,
            shippingId,
            note
            ) values (
                \"$userId\",
                \"$params->sessionId\",
                \"$params->token\",
                \"$params->status\",
                \"$params->tax\",
                \"$params->subTotal\",
                \"$params->voucherId\",
                \"$params->shippingId\",
                \"$params->note\"
            );";

        $res = $this->con->query($qr);
        $last_id = $this->con->query("select id from `order` order by id desc limit 1;");
        $last_id = get_array_from_result($last_id)[0]["id"];
        $products = $params->products;
        foreach ($products as $product) {
            $product = (object)($product);
            $qr = "insert into orderItem (
                productID,
                orderID,
                discount,
                quantity,
                price
                ) values (
                    $product->productID,
                    $last_id,
                    $product->discount,
                    $product->quantity,
                    $product->price
                    )";
            $res = $this->con->query($qr);
            if ($res) {
                continue;
            } else {
                return [false, 'Failed'];
            }
        }
        return $this->getOrderById($last_id);
    }
    function getOrderByUserId($userId)
    {
        $ordersIdQr = "select id from `order` where userId = $userId";
        $ordersId = $this->con->query($ordersIdQr);
        $ordersId =  get_array_from_result($ordersId);
        $orders = [];
        foreach ($ordersId as $orderId) {
            [$status, $order] = $this->getOrderById($orderId["id"]);
            $order = json_decode($order);
            $orderId = ["orderId" => $order->id];
            unset($order->id);
            $order = (object)array_merge($orderId, (array)$order);
            if ($status) {
                $orders[] = $order;
            } else {
                return [False, json_encode(['msg' => "Internal Error"])];
            }
        }
        if (empty($orders)) {
            return [false, json_encode(["msg" => "Not found orders of user with user id: $userId"])];
        } else {
            return [true, json_encode($orders)];
        }
        return [true, json_encode($orders)];
    }
}
