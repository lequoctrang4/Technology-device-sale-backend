<?php

declare(strict_types=1);

namespace Main\Middlewares;

use Sabre\HTTP\Request;
use Sabre\HTTP\Response;

// require_once __DIR__ . "/../core/Controller.php";
class AdminAuthorizationMiddleware
{
    private $next;
    public function __construct($next)
    {
        $this->next = $next;
    }

    public function process(Request $request, Response $response)
    {
        // echo "hello";
        $user = json_decode($request->getHeader('User-info'));
        if (!$user->isAdmin) {
            $response->setStatus(400);
            $response->setBody('Permission denied: Only admin can access this resource!');
            return;
        }
        return $this->next->process($request, $response);

        // $data = [
        // 'method' => $request->getMethod(),
        // 'id' => $request->getParameter('id', -1),
        // ];
        // echo 'm1 ';
        // $response->setContent('hh');
        // return;
        // return;
        // echo 'handle middleware logic';
        // echo $data['id'];
        // Log the request method and URI here
        // echo("{$data['method']} {$data['uri']}");
        // echo "Middleware";

        // Pass the request and response to the next middleware or handler
    }
}
