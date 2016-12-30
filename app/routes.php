<?php
// Routes


use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'index.twig', $args);
});

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
