<?php

namespace core;

class Request {
    private $httpMethod;
    private $uri;
    private $queryParams;
    private $postVars;
    private $putVars;
    private $headers;
    public function __construct(){
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->queryParams = $_GET ?? [];
        $this->postVars = $this->httpMethod === 'POST' ? json_decode(file_get_contents('php://input'), true) : [];
        $this->putVars = $this->httpMethod === 'PUT' ? json_decode(file_get_contents('php://input'), true) : [];
        $this->headers = getallheaders();
        $this->server = $_SERVER;
        $this->setUri();
    }
    private function setUri(){
        $requestUri = $_SERVER['REQUEST_URI'] ?? [];
        $this->uri = explode('?', $requestUri)[0];
    }
    public function __get($attributeName){
        return $this->$attributeName;
    }
}