<?php

namespace source\Model;

use PDO;

class ConnectionDB
{
    private static $instance;
    public static function getConnection()
    {
        self::$instance = new PDO('mysql:dbname=' . $_ENV['DB_NAME'] . ';host=' . $_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return self::$instance;
    }
}
