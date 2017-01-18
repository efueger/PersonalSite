<?php

namespace App\Controllers;

use App\Models\User\UserMapper;
use RKA\Session;
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

        $session = new Session();
        $session->user = $user->getId();
        $this->flash->addMessage('success', 'You have been successfully logged in');

        $redirectTo = $this->router->pathFor('home');

        if (isset($session->redirectUri)) {
            $redirectTo = $session->redirectUri;
            unset($session->redirectUri);
        }

        return $response->withRedirect($redirectTo);
    }

    public function logout(Request $request, Response $response)
    {
        $session = new Session;
        if (!isset($session->user)) {
            return $response->withRedirect($this->router->pathFor('home'));
        }

        Session::destroy();

        return $response->withRedirect($this->router->pathFor('home'));
    }
}
