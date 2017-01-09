<?php

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\VarDumper;

$twigCache = false;

if (getenv('TWIG_CACHE') == true) {
    $twigCache = __DIR__ . '/../cache/templates/';
}

VarDumper::setHandler(function ($var) {
    $cloner = new VarCloner;
    $htmlDumper = new HtmlDumper;
    $htmlDumper->setStyles([
        'default' => 'background-color:#fff; color:#FF8400; line-height:1.2em; font:12px Menlo, Monaco, Consolas, 
        monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:99999; word-break: normal',
        'public' => 'color:#222',
        'protected' => 'color:#222',
        'private' => 'color:#222',
    ]);

    $dumper = PHP_SAPI === 'cli' ? new CliDumper : $htmlDumper;

    $dumper->dump($cloner->cloneVar($var));
    die();
});

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'view' => [
            'template_path' => __DIR__ . '/Views/',
            'template_cache_path' => $twigCache,
            'google_analytics_id' => getenv('GOOGLE_ANALYTICS_ID'),
        ],

        // Monolog settings
        'logger' => [
            'name' => 'personal-site',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'db' => [
            'driver' => getenv('DB_DRIVER'),
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'pass' => getenv('DB_PASS'),
            'port' => getenv('DB_PORT'),
        ],
    ],
];
