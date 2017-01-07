<?php

namespace App\Middleware;

class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $path = $request->getUri()->getPath();

        if ($path != '/logout') {
            if (!isset($_SESSION['user'])) {
                $_SESSION['redirectUri'] = $path;
                return $response->withRedirect($this->container->router->pathFor('auth.getLogin'));
            }
        }

        $response = $next($request, $response);
        return $response;
    }
}
