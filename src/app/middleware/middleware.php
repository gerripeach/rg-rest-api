<?php
/**
 * Copyright (c) 2018 gerripeach <phillip.dev.p@gmail.com>
 * Distributed under the MIT License
 * (See accompanying file LICENSE or a copy at https://opensource.org/licenses/MIT)
 */

$container = $app->getContainer();
$token = $container->get('settings')['auth']['token'];
$app->add(new Slim\Middleware\AuthMiddleware($token));
