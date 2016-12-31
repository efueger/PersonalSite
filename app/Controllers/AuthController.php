<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class AuthController extends Controller
{
    public function getLogin(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/login.twig');
    }

    public function postLogin(Request $request, Response $response)
    {
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if (!$auth) {
            $this->flash->addMessage('error', 'Could not sign you in with those details.');
            return $response->withRedirect($this->router->pathFor('auth.login'));
        }

        $this->flash->addMessage('success', 'You have been logged in.');
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getLogout(Request $request, Response $response)
    {
        $this->auth->logout();

        $this->flash->addMessage('success', 'You have been logged out.');
        return $response->withRedirect($this->router->pathFor('home'));
    }
}