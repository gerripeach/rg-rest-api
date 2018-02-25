<?php
/**
 * Copyright (c) 2018 gerripeach <phillip.dev.p@gmail.com>
 * Distributed under the MIT License
 * (See accompanying file LICENSE or a copy at https://opensource.org/licenses/MIT)
 */

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/get/{code}', function (Request $request, Response $response, array $args) {
    $code = $args['code'];
    $database = $this->get('database');
    $result = $database->getCode($code);
	
	$this->logger->debug("API /invalidate/$code");

    return $response->withJson($result);
});

$app->post('/invalidate/{code}/{guid}', function (Request $request, Response $response, array $args) {
    $code = $args['code'];
    $guid = $args['guid'];
    $database = $this->get('database');
    $result = $database->invalidateCode($code, $guid);
	
	$this->logger->debug("API /invalidate/$code");

    return $response->withJson($result);
});
