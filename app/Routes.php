<?php

use App\Controllers\BlogController;
use App\Controllers\PortfolioController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\User\UserMapper;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'index.twig');
})->setName('home');

/**
 * Blog Routes
 */
$app->get('/blog', BlogController::class . ':index')->setName('blog.index');
$app->get('/blog/new', BlogController::class . ':new')->add(new AuthMiddleware($container))->setName('blog.new');
$app->get('/blog/{slug}', BlogController::class . ':show')->setName('blog.show');
$app->post('/blog', BlogController::class . ':store')->add(new AuthMiddleware($container))->setName('blog.new.post');

/**
 * Portfolio Routes
 */
$app->get('/portfolio', PortfolioController::class . ':index')->setName('portfolio.index');
$app->get('/portfolio/new', PortfolioController::class . ':new')->add(new AuthMiddleware($container))->setName('portfolio.new');
$app->get('/portfolio/{slug}', PortfolioController::class . ':show')->setName('portfolio.show');
$app->post('/portfolio', PortfolioController::class. ':store')->add(new AuthMiddleware($container))->setName('portfolio.store');

/**
 * Auth Routes
 */
$app->get('/login', function (Request $request, Response $response) {
    return $this->view->render($response, 'auth/login.twig');
})->add(new GuestMiddleware($container))->setName('auth.login');

$app->post('/login', function (Request $request, Response $response) {
    $params = $request->getParams();
    $mapper = new UserMapper($this->db);
    $user = $mapper->getUser($params['email']);

    if (!$user) {
        $this->flash->addMessage('danger', 'There was a problem logging you in, please check your email and password');
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    if (!password_verify($params['password'], $user->getPassword())) {
        $this->flash->addMessage('danger', 'There was a problem logging you in, please check your email and password');
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    $_SESSION['user'] = $user->getId();
    $this->flash->addMessage('success', 'You have been successfully logged in');

    return $response->withRedirect($this->router->pathFor('home'));
})->add(new GuestMiddleware($container))->setName('auth.login.post');

$app->get('/logout', function (Request $request, Response $response) {
    unset($_SESSION['user']);
    $this->flash->addMessage('success', 'You have been successfully logged out');

    return $response->withRedirect($this->router->pathFor('home'));
})->add(new AuthMiddleware($container))->setName('auth.logout');
