<?php
require __DIR__ . '/../vendor/autoload.php';
session_start();

// Instantiate the app
if (is_file(__DIR__ . '/../.env')) {
    $dotenv = new \Dotenv\Dotenv(__DIR__ . '/../');
    $dotenv->load();
}

$settings = require __DIR__ . '/../app/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../app/dependencies.php';

// Register routes
require __DIR__ . '/../app/routes.php';

// Run app
$app->run();