<?php

namespace source\Utils;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

trait JwtDecode {
    /**
     * @param Request
     * @return $jsonPayload
     */
    public function jwtDecode($request){
        $jwt = trim(str_replace('Bearear ', '', $request->server['HTTP_AUTHORIZATION']));
        $jsonPayload = (array) JWT::decode($jwt, new Key($_ENV['JWT_KEY'], 'HS256'));
        return $jsonPayload;
    }
}