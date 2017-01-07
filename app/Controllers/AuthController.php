<?php

namespace App\Controllers;

use App\User\UserMapper;
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
            $this->flash->addMessage('danger', 'There was a problem logging you in, please check your email and password');
            return $response->withRedirect($this->router->pathFor('auth.getLogin'));
        }

        if (!password_verify($params['password'], $user->getPassword())) {
            $this->flash->addMessage('danger', 'There was a problem logging you in, please check your email and password');
            return $response->withRedirect($this->router->pathFor('auth.getLogin'));
        }

        $_SESSION['user'] = $user->getId();
        $this->flash->addMessage('success', 'You have been successfully logged in');

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function logout(Request $request, Response $response)
    {
        unset($_SESSION['user']);
        $this->flash->addMessage('success', 'You have been successfully logged out');

        return $response->withRedirect($this->router->pathFor('home'));
    }
}