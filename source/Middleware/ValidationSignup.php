<?php

namespace source\Middleware;

use Exception;

class ValidationSignup
{
    public function handle($request, $next)
    {
        $username = $request->postVars['username'];
        $email = filter_var($request->postVars['email'], FILTER_VALIDATE_EMAIL);
        $password = $request->postVars['password'];
        
        if (!$email || strlen($password) < 8 || strlen($username) < 4) {
            throw new Exception("invalid data", 400);
        }
        
        $next($request);
    }
}