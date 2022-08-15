<?php

require '../vendor/autoload.php';

use source\Config;
use core\Response;

// ENVIROMENT VARIABLES
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable('../', '.env.local');
$dotenv->load();

// ROUTER
use core\Router;
$router = new Router($_ENV['URL_BASE']);
require '../source/Routes.php';

// MIDDLEWARES
use source\Middleware\Middleware;
Middleware::setMapMiddlewares([
    'Authorization' => source\Middleware\Authorization::class,
    'ValidationLogin' => source\Middleware\ValidationLogin::class,
    'ValidationSignup' => source\Middleware\ValidationSignup::class
]);

// EXECUTE ROUTER
$router->dispatch();

// SEND RESPONSE TO CLIENT
Response::sendResponse();