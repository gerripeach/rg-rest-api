<?php
/**
 * Copyright (c) 2018 gerripeach <phillip.dev.p@gmail.com>
 * Distributed under the MIT License
 * (See accompanying file LICENSE or a copy at https://opensource.org/licenses/MIT)
 */

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/{code}', function (Request $request, Response $response, array $args) {
    $code = $args['code'];
    $database = $this->get('database');
    $result = $database->getCode($code);
	
	$this->logger->debug("API - request info for code: $code");

    return $response->withJson($result);
});

$app->post('/{code}/{guid}', function (Request $request, Response $response, array $args) {
    $code = $args['code'];
    $guid = $args['guid'];
    $database = $this->get('database');
    $result = $database->proccessCode($code, $guid);

    $this->logger->debug("API - change state to in_progress for code: $code");

    return $response->withJson($result);
});

$app->put('/{code}/{guid}', function (Request $request, Response $response, array $args) {
    $code = $args['code'];
    $guid = $args['guid'];
    $success = $this->get('database')->invalidateCode($code, $guid);
	
	$this->logger->debug("API - invalidate code: $code");

    if ($success === 0) {
        $this->logger->critical("API - was not able to invalidate code: $code");
        return $response->
            withStatus(500, "Internal Server Error")->
            withAddedHeader(
                'X-InvalidationProblem',
                'fail');
    }
    return $response->withStatus(200, "OK");
});
