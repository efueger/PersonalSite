<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'index.twig', $args);
})->setName('home');

// Blog Routes
$app->get('/blog', function (Request $request, Response $response) {
    $mapper = new PostMapper($this->db);
    $posts = $mapper->getPosts();

    return $this->view->render($response, 'posts/index.twig', ['posts' => $posts]);
});

$app->get('/blog/{slug}', function (Request $request, Response $response, $args) {
    $slug = (string)$args['slug'];
    $mapper = new PostMapper($this->db);
    $post = $mapper->getPostBySlug($slug);

    return $this->view->render($response, 'posts/show.twig', ['post' => $post]);
});

// Portfolio Routes
$app->get('/portfolio', function (Request $request, Response $response) {
    $mapper = new ProjectMapper($this->db);
    $projects = $mapper->getProjects();

    return $this->view->render($response, 'projects/index.twig', ['projects' => $projects]);
});

$app->get('/portfolio/{slug}', function (Request $request, Response $response, $args) {
    $slug = (string)$args['slug'];
    $mapper = new ProjectMapper($this->db);
    $project = $mapper->getProjectBySlug($slug);

    return $this->view->render($response, 'projects/show.twig', ['project' => $project]);
});

// Auth Routes
$app->get('/login', 'AuthController:getLogin')->setName('auth.login');
$app->post('/login', 'AuthController:postLogin');
$app->get('/logout', 'AuthController:getLogout')->setName('auth.logout');