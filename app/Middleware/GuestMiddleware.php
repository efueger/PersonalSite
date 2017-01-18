<?php

namespace App\Middleware;

use RKA\Session;

class GuestMiddleware extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $session = new Session();
        if (isset($session->user)) {
            return $response->withRedirect($this->router->pathFor('home'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
