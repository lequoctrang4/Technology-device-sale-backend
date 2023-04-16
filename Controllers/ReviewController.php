<?php

namespace Main\Controllers;



// use Http\Request;/
// use Http\Response;
use Sabre\HTTP\Request;
use Sabre\HTTP\Response;
use Main\Models\ProductModel;
use Exception;
use Main\Models\ReviewModel;

// require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../Utils/SqlUtils.php";

class ReviewController
{
    private $request;
    private $response;
    private $model;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->model = new ReviewModel();
    }
    function getReviewByProductId()
    {
        // $productId = $productId[0];
        $id = $this->request->getQueryParameters()['id'];
        if ($id != null) {
            [$status, $err] =  $this->model->getReviewByProductId($id);
            if ($status) {
                $this->response->setHeader('Content-type', 'application/json');
                $this->response->setStatus(200);
                $this->response->setBody($err);
            } else {
                $this->response->setStatus(500);
                $this->response->setBody($err);
            }
            return;
        }
        $this->response->setStatus(400);
        $this->response->setBody(json_encode(['err' => 'Bad request']));
    }
    function addReview()
    {
        $data = [
            'review' => json_decode($this->request->getBodyAsString())
        ];
        [$status, $err] = $this->model->addReview($data['review']);
        if ($status) {
            $this->response->setStatus(200);
            $this->response->setHeader('Content-type', 'application/json');
        } else {
            $this->response->setStatus(500);
        }
        $this->response->setBody($err);
    }
    function editReview()
    {
        $data = [
            'review' => json_decode($this->request->getBodyAsString())
        ];
        if ($data['review'] != null) {
            [$status, $err] = $this->model->editReview($data['review']);
            $this->response->setHeader('Content-type', 'application/json');

            if ($status) {
                $this->response->setStatus(200);
            } else {
                $this->response->setStatus(500);
            }
            $this->response->setBody($err);
            return;
        } else {
            $this->response->setStatus(400);
            $this->response->setBody(json_encode(['msg' => 'Missed id']));
            return;
        }
    }
    function deleteReview()
    {
        $id = $this->request->getQueryParameters()['id'];
        if ($id != null) {
            [$status, $err] = $this->model->deleteReview($id);
            if ($status) {
                $this->response->setStatus(200);
                $this->response->setBody($err);
                return;
            } else {
                $this->response->setStatus(500);
                return;
            }
        } else {
            $this->response->setStatus(400);
            $this->response->setBody(json_encode(['msg' => 'Missed id']));
            return;
        }
    }
}
