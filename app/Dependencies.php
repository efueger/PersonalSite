<?php

use Aptoma\Twig\Extension\MarkdownExtension;
use Aptoma\Twig\Extension\MarkdownEngine;
use Slim\Flash\Messages;
use Slim\Views\Twig;

$container = $app->getContainer();

$container['view'] = function ($c) {
    $settings = $c->get('settings')['view'];
    $twig = new Twig($settings['template_path'], [
        'cache' => $settings['template_cache_path']
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $twig->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    $twig->addExtension(new MarkdownExtension(new MarkdownEngine\MichelfMarkdownEngine()));
    $twig['google_analytics_id'] =  $settings['google_analytics_id'];

    return $twig;
};

$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['db'] = function ($c) {
    $db = $c->get('settings')['db'];
    $pdo = new PDO($db['driver'] . ":host=" . $db['host'] . ";dbname=" . $db['name'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$container['flash'] = function () {
    return new Messages();
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($response->withStatus(404), 'errors/404.twig');
    };
};
