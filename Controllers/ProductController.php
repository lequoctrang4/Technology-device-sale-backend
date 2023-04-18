<?php

declare(strict_types=1);

namespace Main\Controllers;

// use Http\Request;/
// use Http\Response;
use Sabre\HTTP\Request;
use Sabre\HTTP\Response;
use Main\Models\ProductModel;
// require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../Utils/SqlUtils.php";


class ProductController
{
    private $request;
    private $response;
    private $model;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->model = new ProductModel();
    }
    function getProducts() // Return: [product]
    {
        [$status, $err] = $this->model->getAllProducts();
        if ($status) {
            $this->response->setStatus(200);
            $this->response->setHeader('Content-type', 'application/json');
            $this->response->setBody($err);
        } else {
            $this->response->setStatus(404);
            $this->response->setBody($err);
        }
        // $body->write($result);
        // $this->response->withBody($body);
        // $this->response->setHeader('Content-type', 'application/json');
        // $this->response->setContent($result);
    }
    function getProductsByCategory() // Return: [product]
    {
        $id = $this->request->getQueryParameters()['categoryId'];
        if ($id != null) {
            [$status, $err] = $this->model->getProductsByCategory($id);
            if ($status) {
                $this->response->setStatus(200);
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setBody($err);
            } else {
                $this->response->setStatus(404);
                $this->response->setBody($err);
            }
            return;
        }
        $this->response->setStatus(404);
        $this->response->setBody('missed category');
        // $body->write($result);
        // $this->response->withBody($body);
        // $this->response->setHeader('Content-type', 'application/json');
        // $this->response->setContent($result);
    }
    function getProductById() //Return type: [product]
    {
        // $data = [
        // 'id' => $this->request->getParameter('id', -1),
        // ];
        $id = $this->request->getQueryParameters()['id'];
        if (!$id) {
            $this->response->setStatus(404);
            $this->response->setBody('Product id is missed');
        }
        [$status, $err]  = $this->model->getProductById($id);
        if ($status) {
            $this->response->setStatus(200);
            $this->response->setHeader('Content-type', 'application/json');
            $this->response->setBody($err);
        } else {
            $this->response->setStatus(404);
            $this->response->setBody($err);
        }
    }

    function createProduct()
    {
        // Takes raw data from the request
        $data = [
            'product' => json_decode($this->request->getBodyAsString())
        ];
        [$status, $err] = $this->model->createProduct($data['product']);
        if ($status) {
            $this->response->setStatus(200);
            $this->response->setHeader('Content-type', 'application/json');
        } else {
            $this->response->setStatus(500);
        }
        $this->response->setBody($err);
    }

    function updateProduct()
    {
        $data = [
            'product' => json_decode($this->request->getBodyAsString())
        ];
        // var_dump(json_decode($data['product']));
        [$status, $err] = $this->model->updateProduct($data['product']);
        if ($status) {
            $this->response->setStatus(200);
            $this->response->setHeader('Content-type', 'application/json');
            $this->response->setBody($data['product']);
        } else {
            $this->response->setStatus(500);
            $this->response->setBody($err);
        }
    }

    function deleteProduct()
    {
        $data = [
            'id' => $this->request->getQueryParameters()['id'],
        ];
        if ($data['id']) {
            [$status, $err] = $this->model->deleteProduct($data['id']);
            if ($status) {
                $this->response->setStatus(200);
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setBody($err);
            } else {
                $this->response->setStatus(404);
                $this->response->setBody($err);
            }
            $this->response->setBody('Delete Product Success');

        } else {
            // $this->response->setContent('Product id is '. $data['id']);
            $this->response->setBody('Product id is missed');
        }
    }
    function getProductByName()
    {
        $name = $this->request->getQueryParameters()['name'];
        if ($name != null) {
            [$status, $err] = $this->model->getProductByName($name);
            if ($status) {
                $this->response->setStatus(200);
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setBody($err);
            } else {
                $this->response->setStatus(404);
                $this->response->setBody($err);
            }
            return;
        } else {
            // $this->response->setContent('Product id is '. $data['id']);
            $this->response->setStatus(404);
            $this->response->setBody('Product id is missed');
        }
    }
    function getCategories()
    {
        [$status, $err] = $this->model->getCategories();
        if ($status) {
            $this->response->setStatus(200);
            $this->response->setHeader('Content-type', 'application/json');
            $this->response->setBody($err);
        } else {
            $this->response->setStatus(404);
            $this->response->setBody($err);
        }
    }
    function getProductAttributes()
    {

        $id = $this->request->getQueryParameters()['id'];
        if ($id != null) {
            [$status, $err] = $this->model->getProductAttributes($id);
            if ($status) {
                $this->response->setStatus(200);
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setBody($err);
            } else {
                $this->response->setStatus(404);
                $this->response->setBody($err);
            }
            return;
        } else {
            // $this->response->setContent('Product id is '. $data['id']);
            $this->response->setStatus(404);
            $this->response->setBody('Product id is missed');
        }
    }
    function getProductByManufacturer()
    {
        $manufactor = $this->request->getQueryParameters()['manufacturer'];
        if ($manufactor != null) {
            [$status, $err] = $this->model->getProductByManufacturer($manufactor);
            if ($status) {
                $this->response->setStatus(200);
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setBody($err);
            } else {
                $this->response->setStatus(404);
                $this->response->setBody($err);
            }
            return;
        } else {
            // $this->response->setContent('Product id is '. $data['id']);
            $this->response->setStatus(404);
            $this->response->setBody('Manufacturer is missed');
        }
    }
    function getProductsHandler()
    {
        $arr = $this->request->getQueryParameters();
        $category = $manufactor = null;
        if (array_key_exists('categoryId', $arr))
            $category = $arr['categoryId'];
        else if (array_key_exists('manufacturer', $arr))
            $manufactor = $arr['manufacturer'];
        if ($category != null) {
            $this->getProductsByCategory();
            return;
        }
        if ($manufactor != null) {
            $this->getProductByManufacturer();
            return;
        }
        $this->getProducts();
        return;
    }
}
