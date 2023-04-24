<?php

declare(strict_types=1);

namespace Main\Controllers;

use Main\Models\OrderModel;
use Sabre\HTTP\Request;
use Sabre\HTTP\Response;
use Main\Models\OrderModels;

require_once __DIR__ . "/../Utils/SqlUtils.php";

class OrderController
{
    private $request;
    private $response;
    private $model;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->model = new OrderModel();
    }
    function getOrdersByUserId() // Return: [product]
    {
        $user = json_decode($this->request->getHeader('User-info'));
        [$status, $err] = $this->model->getOrderByUserId($user->id);
        if ($status) {
            $this->response->setStatus(200);
            $this->response->setHeader('Content-type', 'application/json');
            $this->response->setBody($err);
        } else {
            $this->response->setHeader('Content-type', 'application/json');
            $this->response->setStatus(404);
            $this->response->setBody($err);
        }
    }
    function getOrderByOrderId()
    {
        $id = $this->request->getQueryParameters()['id'];
        if ($id != null) {
            [$status, $err] = $this->model->getOrderById($id);
            if ($status) {
                $this->response->setStatus(200);
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setBody($err);
            } else {
                $this->response->setStatus(404);
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setBody($err);
            }
            return;
        }
        $this->response->setStatus(404);
        $this->response->setBody(json_encode(['msg' => 'missed Id']));
    }
    function createOrder()
    {
        $user = json_decode($this->request->getHeader('User-info'));
        $order = json_decode($this->request->getBodyAsString());
        [$status, $err] = $this->model->createOrder($user->id, $order);
        if ($status) {
            $this->response->setStatus(200);
            $this->response->setHeader('Content-type', 'application/json');
            $this->response->setBody($err);
        } else {
            $this->response->setHeader('Content-type', 'application/json');
            $this->response->setStatus(404);
            $this->response->setBody($err);
        }
    }
}
