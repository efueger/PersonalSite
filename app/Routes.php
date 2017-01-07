<?php

use App\Blog\PostEntity;
use App\Blog\PostMapper;
use App\Controllers\BlogController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Portfolio\ProjectEntity;
use App\Portfolio\ProjectMapper;
use App\User\UserMapper;
use Cocur\Slugify\Slugify;
use Slim\Exception\NotFoundException;
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
$app->get('/portfolio', function (Request $request, Response $response) {
    $mapper = new ProjectMapper($this->db);
    $projects = $mapper->getProjects();

    return $this->view->render($response, 'projects/index.twig', ['projects' => $projects]);
})->setName('portfolio.index');

$app->post('/portfolio', function (Request $request, Response $response) {
    $params = $request->getParams();

    $files = $request->getUploadedFiles();
    $previewImage = $files['preview'];
    $previewImageFileName = $previewImage->getClientFilename();
    $previewImage->moveTo(__DIR__ . "/../public/img/projects/{$previewImageFileName}");

    $slugify = new Slugify();
    $slug = $slugify->slugify($params['title']);
    $projectData = [
        'title' => $params['title'],
        'slug' => $slug,
        'description' => $params['description'],
        'live_url' => $params['live_url'],
        'github_url' => $params['github_url'],
        'technologies' => $params['technologies'],
        'preview' => $previewImageFileName,
        'published_at' => $params['published_at'],
    ];

    $project = new ProjectEntity($projectData);
    $projectMapper = new ProjectMapper($this->db);
    $projectMapper->save($project);

    return $response->withRedirect($this->router->pathFor('portfolio.index'));
})->add(new AuthMiddleware($container))->setName('portfolio.new.post');

$app->get('/portfolio/new', function (Request $request, Response $response) {
    return $this->view->render($response, 'projects/new.twig');
})->add(new AuthMiddleware($container))->setName('portfolio.new');

$app->get('/portfolio/{slug}', function (Request $request, Response $response, $args) {
    $slug = (string)$args['slug'];
    $mapper = new ProjectMapper($this->db);
    $project = $mapper->getProjectBySlug($slug);

    if (!$project) {
        throw new NotFoundException($request, $response);
    }

    return $this->view->render($response, 'projects/show.twig', ['project' => $project]);
})->setName('portfolio.show');

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
