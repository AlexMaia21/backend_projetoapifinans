<?php

namespace source\Middleware;

use Exception;

class ValidationLogin
{
    public function handle($request, $next)
    {
        $email = filter_var($request->postVars['email'], FILTER_VALIDATE_EMAIL);
        $password = $request->postVars['password'];
        if (!$email || strlen($password) < 8) {
            throw new Exception("invalid data", 400);
        }
        $next($request);
    }
}