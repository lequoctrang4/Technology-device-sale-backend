<?php

declare(strict_types=1);

namespace Main\Middlewares;

use Sabre\HTTP\Request;
use Sabre\HTTP\Response;
use Exception;
use Main\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
        if (!isset(apache_request_headers()["Authorization"]) || !preg_match('/Bearer\s(\S+)/', apache_request_headers()["Authorization"], $matches)) {
            $response->setStatus(400);
            $response->setBody("Cannot find token!");
            return;
        }
        $token = $matches[1];
        $key = "privatekey";
        try{
            $user = JWT::decode($token, new Key($key, 'HS256'));
            $userModel = new UserModel();
            [$status, $err] = $userModel->checkUserExistence($user->mobile);
            if (!$status) {
                throw new Exception("Cannot find authenticate: User not exists!", 404);
            }
            if (!$user->isAdmin) {
                $response->setStatus(400);
                $response->setBody('Permission denied: Only admin can access this resource!');
                return;
            }
            $request->setHeader('User-info', json_encode($user));
        }

         catch (\Firebase\JWT\ExpiredException $e) {
            // errors having to do with environmental setup or malformed JWT Keys
            // $response->setStatus($e->getCode());
            // $response->setBody($e->getMessage());
            #TODO: handle refresh token
            return;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            // errors having to do with JWT signature and claims
            // errors having to do with environmental setup or malformed JWT Keys
            // $response->setStatus($e->getCode());
            // $response->setBody($e->getMessage());
            return;
        } catch (Exception $e) {
            // errors having to do with environmental setup or malformed JWT Keys
            // $response->setStatus($e->getCode());
            $response->setStatus(400);
            $response->setBody($e->getMessage());
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
