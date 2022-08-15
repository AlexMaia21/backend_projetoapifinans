<?php

namespace core;

class Response
{
    private static $content = [];
    private static $httpCode = 200;
    public static function amountResponse($content, $httpCode = 200)
    {
        self::$content = $content;
        self::$httpCode = $httpCode;
    }
    private static function sendHeaders()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD");
        header("Access-Control-Allow-Headers: Authorization, Content-Type");
    }
    public static function sendResponse()
    {
        self::sendHeaders();

        if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){
            http_response_code(200);
        } else {
            http_response_code(self::$httpCode);
        }
        $json = json_encode(self::$content, JSON_UNESCAPED_UNICODE);
        echo $json;
    }
}