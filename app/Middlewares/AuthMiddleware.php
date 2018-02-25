<?php namespace Slim\Middleware;
/**
 * Copyright (c) 2018 gerripeach <phillip.dev.p@gmail.com>
 * Distributed under the MIT License
 * (See accompanying file LICENSE or a copy at https://opensource.org/licenses/MIT)
 */

use Slim\Http\Request;
use Slim\Http\Response;

class AuthMiddleware
{
    private $token_;

    public function __construct($token) {
        $this->token_ = $token;
    }

    public function __invoke(Request $request, Response $response, $next) {
        $header = $request->getHeaderLine('Authorization');
        if (!$this->authenticate($header))
            return $this->denyAccess($response);

        // everything ok. continue.
        return $next($request, $response);
    }

    public function authenticate(string $header) {
        if (preg_match('/Bearer\s+(.*)$/', $header, $matches))
            return $this->token_ === $matches[1];
        return false;
    }

    public function denyAccess(Response $response) {
        return $response->
            withStatus(401, 'Unauthorized')->
            withHeader(
                'WWW-Authenticate',
                'Bearer');
    }
}
