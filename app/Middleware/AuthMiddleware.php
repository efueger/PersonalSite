<?php

namespace App\Middleware;

class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!isset($_SESSION['user'])) {
            return $response->withRedirect($this->container->router->pathFor('auth.getLogin'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
