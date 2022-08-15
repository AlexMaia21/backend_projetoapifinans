<?php

namespace source\Middleware;

/**
 * Classe responsável pelos middlewares
 */
class Middleware {
    private static $mapMiddlewares;
    private static $defaultMiddlewares = [];
    private $routeMiddlewares;
    private $controller;
    private $methodController;
    private $controllerArgs;
    public function __construct($middlewares, $controller, $methodController, $args){
        $this->routeMiddlewares = array_merge(self::$defaultMiddlewares, $middlewares);
        $this->methodController = $methodController;
        $this->controller = $controller;
        $this->controllerArgs = $args;
    }
    public static function setMapMiddlewares($middlewares){
        self::$mapMiddlewares = $middlewares;
    }
    public static function setMapDefaultMiddlewares($middlewares){
        self::$defaultMiddlewares = $middlewares;
    }
    public function next($request){
        if(empty($this->routeMiddlewares)){
            call_user_func_array([new $this->controller, $this->methodController], [$request, $this->controllerArgs]);
            return;
        }

        // EXCLUDE FIRST INDEX ARRAY MIDDLEWARES
        $currentMiddleware = array_shift($this->routeMiddlewares);

        if(!isset(self::$mapMiddlewares[$currentMiddleware])){
            throw new \Exception('middleware error', 500);
        }

        $currentInstance = $this;
        $nextMiddleware = function($request) use ($currentInstance){
            return $currentInstance->next($request);
        };

        // EXECUTE MIDDLEWARE
        return (new self::$mapMiddlewares[$currentMiddleware])->handle($request, $nextMiddleware);
    }
}