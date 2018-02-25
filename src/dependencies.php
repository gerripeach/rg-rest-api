<?php
/**
 * Copyright (c) 2018 gerripeach <phillip.dev.p@gmail.com>
 * Distributed under the MIT License
 * (See accompanying file LICENSE or a copy at https://opensource.org/licenses/MIT)
 */

$container = $app->getContainer();

// database connection
$container['database'] = function ($c) {
    $settings = $c->get('settings')['database'];
    return new Slim\Database\Database($settings['host'], $settings['port'], $settings['user'], $settings['pass'], $settings['base'], $settings['db_name']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
