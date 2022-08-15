<?php

namespace source\Middleware;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class Authorization {
    public function handle($request, $next){
        $jwt = trim(str_replace('Bearear ', '', $request->server['HTTP_AUTHORIZATION']));
        try {
            JWT::decode($jwt, new Key($_ENV['JWT_KEY'], 'HS256'));
        } catch(\Throwable $e) {
            throw new Exception('unauthorized', 401);
        }
        $next($request);
    }
}