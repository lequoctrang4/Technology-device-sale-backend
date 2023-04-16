<?php

declare(strict_types=1);

namespace Main\core;

use Main\Controllers\AbstractController;
use Main\Middlewares\CustomMiddleware;
use Sabre\HTTP;

require __DIR__ . '/../vendor/autoload.php';
$injector = include('Dependencies.php');
$request = HTTP\Sapi::getRequest();
$response = new HTTP\Response();

// $request = $injector->make('Http\HttpRequest');
// $response = $injector->make('Http\HttpResponse');
// $sapi = $injector->make('Sabre\HTTP\Sapi');
// $request = $sapi->getRequest();
// $response = $injector->make('Sabre\HTTP\Response');
// $this->response->setHeader('Content-type', 'application/json');
$config = include("RouterConfig.php");
// var_dump($config["user"]["routes"]);
$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) use ($config) {
    foreach ($config as $role) {
        $routes = $role['routes'];
        foreach ($routes as $route) {
            $r->addRoute($route[0], $route[1], $route[2]);
        };
    }
};

// $publicRouteDefinitionCallback = function (\FastRoute\RouteCollector $r) {
//     $routes = include('Public.php');
//     foreach ($routes as $route) {
//         $r->addRoute($route[0], $route[1], $route[2]);
//     }
// };

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);

// $routeInfo = $dispatcher->dispatch($request->getMethod(), '/' . $request->getPath());
// switch ($routeInfo[0]) {
//     case \FastRoute\Dispatcher::NOT_FOUND:
//         $response->setBody('404 - Page not found');
//         $response->setStatus(404);
//         break;
//     case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
//         $response->setBody('405 - Method not allowed');
//         $response->setStatus(405);
//         break;
//     case \FastRoute\Dispatcher::FOUND:
//         $className = $routeInfo[1][0];
//         $method = $routeInfo[1][1];
//         $vars = $routeInfo[2];
//         $handler = new AbstractController($className, $method, $vars, $injector);
//         $ms = [];
//         // $ms = include('MiddlewaresQueue.php');
//         foreach ($ms as $key) {
//             // echo $key;
//             // echo "hello";
//             $handler = $injector->make($key, [$handler]);
//         }
//         $handler->process($request, $response);
//         break;
// }
// foreach ($response->getHeaders() as $header) {
//     header($header[0], false);
// }
// // foreach ($response->getHeaders() as $header) {
// //     header($header, false);
// // }
// // echo $response->getContent();


// // $MiddlewareRouteCallback = function (\FastRoute\RouteCollector $r) {
// //     $routes = include('MiddlewareRoutes.php');

// //     foreach ($routes as $route) {
// //         $r->addRoute($route[0], $route[1], $route[2]);
// //     }
// // };
// // $dispatcher = \FastRoute\simpleDispatcher($MiddlewareRouteCallback);
$routePath =  '/' . $request->getPath();
$routeMethod = $request->getMethod();
$routeInfo = $dispatcher->dispatch($routeMethod, $routePath);
$middlewaresQueue = [];
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $response->setBody('404 - Page not found');
        $response->setStatus(404);
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->setBody('405 - Method not allowed');
        $response->setStatus(405);
        break;
    case \FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];
        $handler = new AbstractController($className, $method, $vars, $injector);
        $routePath =  '/' . $request->getPath();
        $routeMethod = $request->getMethod();
        foreach ($config as $key => $value) {
            $middlewaresQueue = array_merge($middlewaresQueue, $value["middlewares"]);
            foreach ($value["routes"] as $route) {
                if ($routePath == $route[1] && $routeMethod == $route[0]) {
                    foreach ($middlewaresQueue as $middleware) {
                        $handler = $injector->make($middleware, [$handler]);
                    }
                    break 2;
                }
            }
        };
        // $ms = include('MiddlewaresQueue.php');
        $handler->process($request, $response);
        break;
}
foreach ($response->getHeaders() as $header) {
    header($header[0], false);
}
HTTP\Sapi::sendResponse($response);

// echo $response->getContent();
