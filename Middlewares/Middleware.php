<?php

declare(strict_types=1);

namespace Main\Middlewares;

use Sabre\HTTP\Request;
use Sabre\HTTP\Response;
// require_once __DIR__ . "/../core/Controller.php";
interface Middleware
{
    function process(Request $request, Response $response);
}
