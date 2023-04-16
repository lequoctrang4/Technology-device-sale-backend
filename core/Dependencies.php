<?php

declare(strict_types=1);

$injector = new \Auryn\Injector;
// $injector->alias('Sabre\HTTP\Request', 'Sabre\HTTP\RequestInterface');
// $injector->alias('Sabre\HTTP\RequestInterface', 'Sabre\HTTP\MessageInterface');
// $injector->share('Sabre\HTTP\Request');
// $injector->define('Sabre\HTTP\Request', [
// ':get' => $_GET,
// ':post' => $_POST,
// ':cookies' => $_COOKIE,
// ':files' => $_FILES,
// ':server' => $_SERVER,
// ':inputStream' => file_get_contents('php://input')
// ]);

// $injector->alias('Sabre\HTTP\ResponseInterface', 'Sabre\HTTP\Response');
// $injector->alias('Sabre\HTTP\MessageInterface', 'Sabre\HTTP\ResponseInterface');

$injector->share('Sabre\HTTP\Response');
$injector->define('Sabre\HTTP\Response', []);
$injector->share('Sabre\HTTP\Sapi');
$injector->define('Sabre\HTTP\Sapi', []);

return $injector;
