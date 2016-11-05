<?php
defined('ROOT') || define('ROOT', dirname(__FILE__));
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

if (PHP_SAPI === 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require ROOT . '/vendor/autoload.php';

// Instantiate the app
$settings = require ROOT . '/src/settings.php';
$container = new \Slim\Container($settings);
$app = new \Slim\App($container);

// Set up dependencies
require ROOT . '/src/dependencies.php';

// Register middleware
require ROOT . '/src/middleware.php';

// Register routes
require ROOT . '/src/routes.php';

// Run app
$app->run();
