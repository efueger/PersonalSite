<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$dotenv = new \Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();
$settings = require __DIR__ . '/../app/Settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../app/Dependencies.php';

// Register routes
require __DIR__ . '/../app/Routes.php';

// Run app
$app->run();
