<?php

namespace core;

use Exception;
use core\Request;
use source\Middleware\Middleware;

class Router
{
    private $urlBase;
    private $prefix;
    private $routes = [];
    /**
     * @var Request
     */
    private $request;
    public function __construct($urlBase = '')
    {
        $this->urlBase = $urlBase;
        $this->request = new Request;
        $this->setPrefixUrlBase();
    }
    public function get($route, $controller = '', $middlewares = [])
    {
        $this->addRoute('get', $route, $controller, $middlewares);
    }
    public function post($route, $controller = '', $middlewares = [])
    {
        $this->addRoute('post', $route, $controller, $middlewares);
    }
    public function put($route, $controller = '', $middlewares = [])
    {
        $this->addRoute('put', $route, $controller, $middlewares);
    }
    public function delete($route, $controller = '', $middlewares = [])
    {
        $this->addRoute('delete', $route, $controller, $middlewares);
    }
    private function setPrefixUrlBase()
    {
        $parse = parse_url($this->urlBase);
        $this->prefix = $parse['path'];
    }
    private function addRoute($method, $route, $controller, $middlewares)
    {
        $method = strtoupper($method);

        $regex = '/{(.*?)}/';
        $matchesVars = [];
        if(preg_match_all($regex, $route, $matches)){
            $route = preg_replace($regex, '(.*?)', $route);
            $matchesVars = $matches[1];
        }

        $regexRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        $this->routes[$regexRoute][$method]['controller'] = $controller;
        $this->routes[$regexRoute][$method]['vars'] = $matchesVars;
        $this->routes[$regexRoute][$method]['request'] = $this->request;
        $this->routes[$regexRoute][$method]['middlewares'] = $middlewares;
    }
    private function getRoute()
    {
        $uri = $this->uri();
        $httpMethod = $this->request->httpMethod;

        foreach($this->routes as $regexRoute => $method){
            if(preg_match($regexRoute, $uri, $matches)){
                if(isset($method[$httpMethod])){
                    unset($matches[0]);
                    $keyVars = $method[$httpMethod]['vars'];
                    $method[$httpMethod]['vars'] = array_combine($keyVars, $matches);
                    return $method[$httpMethod];
                }
                throw new Exception('HTTP method not allowed', 405);
            }
        }
        throw new Exception('Page not found', 404);
    }
    private function uri()
    {
        $uri = $this->request->uri;
        return strlen($this->prefix) ? explode($this->prefix, $uri)[1] : '';
    }
    public function dispatch()
    {
        try {
            $controller = explode(':', $this->getRoute()['controller']);
            $vars = $this->getRoute()['vars'];
            $request = $this->getRoute()['request'];
            $middlewares = $this->getRoute()['middlewares'];

            $class = 'source\Controller\\' . $controller[0];
            $method = $controller[1];
            (new Middleware($middlewares, $class, $method, $vars))->next($request);

        } catch(\Throwable $e){
            $classHome = '\source\Controller\\ErrorController';
            call_user_func_array([new $classHome, 'error'], [$e->getMessage(), $e->getCode()]);
        }
    }
}
