<?php

use App\Controllers\AuthController;
use App\Controllers\BlogController;
use App\Controllers\PagesController;
use App\Controllers\PortfolioController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', PagesController::class . ':index')
    ->setName('home');

/**
 * Blog Routes
 */
$app->get('/blog', BlogController::class . ':index')
    ->setName('blog.index');
$app->get('/blog/new', BlogController::class . ':new')
    ->add(new AuthMiddleware($container))
    ->setName('blog.new');
$app->get('/blog/{slug}', BlogController::class . ':show')
    ->setName('blog.show');
$app->post('/blog', BlogController::class . ':store')
    ->add(new AuthMiddleware($container))
    ->setName('blog.store');

/**
 * Portfolio Routes
 */
$app->get('/portfolio', PortfolioController::class . ':index')
    ->setName('portfolio.index');
$app->get('/portfolio/new', PortfolioController::class . ':new')
    ->add(new AuthMiddleware($container))
    ->setName('portfolio.new');
$app->get('/portfolio/{slug}', PortfolioController::class . ':show')
    ->setName('portfolio.show');
$app->post('/portfolio', PortfolioController::class. ':store')
    ->add(new AuthMiddleware($container))
    ->setName('portfolio.store');

/**
 * Auth Routes
 */
$app->get('/login', AuthController::class . ':getLogin')
    ->add(new GuestMiddleware($container))
    ->setName('auth.getLogin');
$app->post('/login', AuthController::class . ':postLogin')
    ->add(new GuestMiddleware($container))
    ->setName('auth.postLogin');
$app->get('/logout', AuthController::class . ':logout')
    ->add(new AuthMiddleware($container))
    ->setName('auth.logout');

/**
 * Admin Routes
 */
$app->get('/admin', PagesController::class. ':adminIndex')
    ->add(new AuthMiddleware($container))
    ->setName('admin.index');
