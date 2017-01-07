<?php

namespace App\Middleware;

class GuestMiddleware extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        if (isset($_SESSION['user'])) {
            return $response->withRedirect($this->router->pathFor('home'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
