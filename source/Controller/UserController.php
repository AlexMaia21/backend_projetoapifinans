<?php

namespace source\Controller;

use core\Request;
use core\Response;
use Exception;
use source\Model\UserDao;
use Firebase\JWT\JWT;
use source\Utils\JwtDecode;

class UserController
{
    /**
     * trait JwtDecode
     */
    use JwtDecode;
    public function filterSanitize($arg)
    {
        $sanitizeString = filter_var($arg, FILTER_SANITIZE_SPECIAL_CHARS);
        $sanitizeString = filter_var($sanitizeString, FILTER_SANITIZE_ADD_SLASHES);
        return $sanitizeString;
    }
    public function getDataUser(Request $request){
        $idUser = $this->jwtDecode($request)['sub'];

        $userDao = new UserDao;
        $user = $userDao->selectUserById($idUser);

        Response::amountResponse(['username' => $user['username'], 'email' => $user['email']]);
    }
    public function login(Request $request)
    {
        $email = $request->postVars['email'];

        $userDao = new UserDao;
        $user = $userDao->selectUserByEmail($email);

        if (count($user)) {
            $password = $request->postVars['password'];
            if (!password_verify($password, $user['password_hash'])) {
                throw new Exception('Nome ou senha inválidos', 401);
            } else {
                $payloadJwt = [
                    'username' => $user['username'],
                    'sub' => $user['id_user'],
                    'iat' => time(),
                    'exp' => time() + 86400,
                    'aud' => 'http://localhost:3000'
                ];
                Response::amountResponse(['jwt' => JWT::encode($payloadJwt, $_ENV['JWT_KEY'], 'HS256')]);
            }
        } else {
            throw new Exception('User not found', 400);
        }
    }
    /**
     * Create User
     */
    public function signup(Request $request)
    {
        $username = $this->filterSanitize($request->postVars['username']);
        $email = $request->postVars['email'];
        $password_hash = password_hash($request->postVars['password'], PASSWORD_DEFAULT);
        $id_user = md5(uniqid());

        $userDao = new UserDao;
        $userDao->createUser($id_user, $username, $email, $password_hash);
        Response::amountResponse(['sucess' => 'created'], 201);
    }
    public function authUser()
    {
        Response::amountResponse('authorized');
    }
}
