<?php

declare(strict_types=1);

namespace Main\Controllers;

use Sabre\HTTP\Request;
use Sabre\HTTP\Response;
use Main\Middlewares\Middleware;

class AbstractController implements Middleware
{
    private $className;
    private $method;
    private $vars;
    private $injector;
    function __construct($className, $method, $vars, $injector)
    {
        $this->className = $className;
        $this->method = $method;
        $this->vars = $vars;
        $this->injector = $injector;
    }
    function process(Request $request, Response $response)
    {
        $class = $this->injector->make($this->className, [$request, $response]);
        $class->{$this->method}($this->vars);
    }
}

// $requestHandler = function (ServerRequestInterface $request): ResponseInterface {
//     // Do something to handle the request and return a response.
//     // $response = withBody
//     return new ResponseInterface;
// };
