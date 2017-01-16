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
$app->get('/blog/{slug}', BlogController::class . ':show')
    ->setName('blog.show');

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
$app->group('/admin', function () use ($app) {
    $app->get('', '\App\Controllers\PagesController:adminIndex')->setName('admin.index');

    $app->group('/blog', function () use ($app) {
        $app->get('/published', '\App\Controllers\BlogController:listPublished')->setName('admin.blog.published');
        $app->get('/draft', '\App\Controllers\BlogController:listDraft')->setName('admin.blog.draft');
        $app->get('/new', '\App\Controllers\BlogController:new')->setName('admin.blog.new');
        $app->post('', '\App\Controllers\BlogController:store')->setName('admin.blog.store');
        $app->get('/{slug}/delete', '\App\Controllers\BlogController:destroy')->setName('admin.blog.destroy');
        $app->get('/{slug}', '\App\Controllers\BlogController:edit')->setName('admin.blog.edit');
        $app->post('/{slug}', '\App\Controllers\BlogController:update')->setName('admin.blog.update');
    });

    $app->group('/portfolio', function () use ($app) {
        $app->get('/published', '\App\Controllers\PortfolioController:listPublished')
            ->setName('admin.portfolio.published');
        $app->get('/draft', '\App\Controllers\PortfolioController:listDraft')->setName('admin.portfolio.draft');
        $app->get('/new', '\App\Controllers\PortfolioController:new')->setName('admin.portfolio.new');
        $app->post('', '\App\Controllers\PortfolioController:store')->setName('admin.portfolio.store');
    });
})->add(new AuthMiddleware($container));
