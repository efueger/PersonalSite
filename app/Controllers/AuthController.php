<?php

namespace App\Controllers;

use App\Models\User\UserMapper;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController extends BaseController
{
    public function getLogin(Request $request, Response $response)
    {
        return $this->view->render($response, 'auth/login.twig');
    }

    public function postLogin(Request $request, Response $response)
    {
        $params = $request->getParams();
        $mapper = new UserMapper($this->db);
        $user = $mapper->getUser($params['email']);

        if (!$user) {
            $this->flash->addMessage('danger', 'There was a problem logging you in, please check your credentials');
            return $response->withRedirect($this->router->pathFor('auth.getLogin'));
        }

        if (!password_verify($params['password'], $user->getPassword())) {
            $this->flash->addMessage('danger', 'There was a problem logging you in, please check your credentials');
            return $response->withRedirect($this->router->pathFor('auth.getLogin'));
        }

        $_SESSION['user'] = $user->getId();
        $this->flash->addMessage('success', 'You have been successfully logged in');

        $redirectTo = $this->router->pathFor('home');

        if (isset($_SESSION['redirectUri'])) {
            $redirectTo = $_SESSION['redirectUri'];
            unset($_SESSION['redirectUri']);
        }

        return $response->withRedirect($redirectTo);
    }

    public function logout(Request $request, Response $response)
    {
        if (!isset($_SESSION['user'])) {
            return $response->withRedirect($this->router->pathFor('home'));
        }

        unset($_SESSION['user']);
        $this->flash->addMessage('success', 'You have been successfully logged out');

        return $response->withRedirect($this->router->pathFor('home'));
    }
}
