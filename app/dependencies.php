<?php
// DIC configuration
use App\Controllers\AuthController;
use Aptoma\Twig\Extension\MarkdownExtension;
use Aptoma\Twig\Extension\MarkdownEngine;

$container = $app->getContainer();

// view renderer
$container['view'] = function ($c) {
    $settings = $c->get('settings')['view'];
    $view = new \Slim\Views\Twig($settings['template_path'], [
        'cache' => $settings['template_cache_path']
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    $view->addExtension(new Twig_Extensions_Extension_Text());
    $engine = new MarkdownEngine\MichelfMarkdownEngine();
    $view->addExtension(new MarkdownExtension($engine));

    $view->getEnvironment()->addGlobal('flash', $c->flash);

    return $view;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($c) use ($capsule) {
    return $capsule;
};

$container['auth'] = function ($c) {
    return new \App\Auth\Auth;
};

$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};

$container['AuthController'] = function ($container) {
    return new AuthController($container);
};