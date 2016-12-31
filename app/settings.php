<?php

$twigCache = false;

if(getenv('TWIG_CACHE') == true) {
    $twigCache = __DIR__ . '/../resources/templates/cache/';
}

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'view' => [
            'template_path' => __DIR__ . '/../resources/templates/',
            'template_cache_path' => $twigCache,
        ],

        // Monolog settings
        'logger' => [
            'name' => 'personal-site',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'db' => [
            'driver' => 'mysql',
            'host' => getenv('DB_HOST'),
            'database' => getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'pass' => getenv('DB_PASS'),
            'port' => getenv('DB_PORT'),
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
    ],
];
