<?php
/**
 * Copyright (c) 2018 gerripeach <phillip.dev.p@gmail.com>
 * Distributed under the MIT License
 * (See accompanying file LICENSE or a copy at https://opensource.org/licenses/MIT)
 */

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
$settings = require __DIR__ . '/../src/app/config/config.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';
require __DIR__ . '/../src/database/Database.php';

// Register middleware
require __DIR__ . '/../src/app/middleware/AuthMiddleware.php';
require __DIR__ . '/../src/app/middleware/middleware.php';

// Register routes
require __DIR__ . '/../src/app/routes/routes.php';

// Run app
$app->run();
