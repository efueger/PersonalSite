<?php

namespace App\Middleware;

use RKA\Session;

class AuthMiddleware extends BaseMiddleware
{
    public function __invoke($request, $response, $next)
    {
        $session = new Session();
        $path = $request->getUri()->getPath();

        if ($path != '/logout') {
            if (!isset($session->user)) {
                $session->redirectUri = $path;
                return $response->withRedirect($this->router->pathFor('auth.getLogin'));
            }
        }

        $response = $next($request, $response);
        return $response;
    }
}
