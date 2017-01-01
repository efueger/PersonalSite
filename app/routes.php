<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Cocur\Slugify\Slugify;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function ($request, $response) {
    return $this->view->render($response, 'index.twig');
})->setName('home');

/**
 * Blog Routes
 */
$app->get('/blog', function (Request $request, Response $response) {
    $mapper = new PostMapper($this->db);
    $posts = $mapper->getPosts();

    return $this->view->render($response, 'posts/index.twig', ['posts' => $posts]);
})->setName('blog.index');

$app->post('/blog', function (Request $request, Response $response) {
    $params = $request->getParams();
    $slugify = new Slugify();
    $slug = $slugify->slugify($params['title']);
    $postData = [
        'title' => $params['title'],
        'slug' => $slug,
        'content' => $params['content'],
        'published_at' => $params['published_at'],
    ];

    $post = new PostEntity($postData);
    $postMapper = new PostMapper($this->db);
    $postMapper->save($post);

    return $response->withRedirect($this->router->pathFor('blog.index'));
})->add(new AuthMiddleware($container));

$app->get('/blog/new', function (Request $request, Response $response) {
    return $this->view->render($response, 'posts/new.twig');
})->add(new AuthMiddleware($container));

$app->get('/blog/{slug}', function (Request $request, Response $response, $args) {
    $slug = (string)$args['slug'];
    $mapper = new PostMapper($this->db);
    $post = $mapper->getPostBySlug($slug);

    if ($post == false) {
        throw new \Slim\Exception\NotFoundException($request, $response);
    }

    return $this->view->render($response, 'posts/show.twig', ['post' => $post]);
});

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
    $previewImage->moveTo(__DIR__."/../public/img/projects/{$previewImageFileName}");

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
})->add(new AuthMiddleware($container));

$app->get('/portfolio/new', function (Request $request, Response $response) {
    return $this->view->render($response, 'projects/new.twig');
})->add(new AuthMiddleware($container));

$app->get('/portfolio/{slug}', function (Request $request, Response $response, $args) {
    $slug = (string)$args['slug'];
    $mapper = new ProjectMapper($this->db);
    $project = $mapper->getProjectBySlug($slug);

    if (!$project) {
        throw new \Slim\Exception\NotFoundException($request, $response);
    }

    return $this->view->render($response, 'projects/show.twig', ['project' => $project]);
});

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
        return $response->withRedirect($this->router->pathFor('auth.login'));
    }

    if (password_verify($params['password'], $user->getPassword())) {
        $_SESSION['user'] = $user->getId();
        return $response->withRedirect($this->router->pathFor('home'));
    }

    return $response->withRedirect($this->router->pathFor('auth.login'));
})->add(new GuestMiddleware($container));

$app->get('/logout', function (Request $request, Response $response) {
    unset($_SESSION['user']);

    return $response->withRedirect($this->router->pathFor('home'));
})->add(new AuthMiddleware($container))->setName('auth.logout');