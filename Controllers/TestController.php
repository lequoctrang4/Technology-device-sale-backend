<?php

declare(strict_types=1);

namespace Main\Controllers;

use Sabre\HTTP\Request;
use Sabre\HTTP\Response;

// use Http\Request;
// use Http\Response;

class TestController
{
    private $request;
    private $response;
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    function TestFunction()
    {
        // echo $this->request->getPath();
        // echo "final function";
        $header = $this->request->getHeader('info');
        $data = json_decode($this->request->getBodyAsString());
        if ($data) {
            $this->response->setBody('header receive at test function: ' . $header);
        } else {
            $this->response->setStatus(404);
            $this->response->setBody('Not found id');
            return;
        }
    }
}

// $requestHandler = function (ServerRequestInterface $request): ResponseInterface {
//     // Do something to handle the request and return a response.
//     // $response = withBody
//     return new ResponseInterface;
// };
