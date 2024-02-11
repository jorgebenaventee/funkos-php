<?php

namespace config;


use Dotenv\Dotenv;
use PDO;

require_once __DIR__ . '/../../vendor/autoload.php';

class Config
{
    private static Config $instance;
    private PDO $db;

    private $rootPath = '/var/www/html';
    private $uploadPath = '/var/www/html/public/uploads/';
    private $uploadUrl = 'http://localhost/uploads/';

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable($this->rootPath);
        $dotenv->load();


        $mySqlUser = $_ENV['DB_USERNAME'] ?? 'root';
        $mySqlPass = $_ENV['DB_PASSWORD'] ?? 'root';
        $mySqlHost = $_ENV['DB_HOST'] ?? 'funkos-php-mysql-1';
        $mySqlDb = $_ENV['DB_DATABASE'] ?? 'funkos';
        $mySqlPort = $_ENV['DB_PORT'] ?? '3306';
        $this->db = new PDO("mysql:host=$mySqlHost;port=$mySqlPort;dbname=$mySqlDb", $mySqlUser, $mySqlPass);
    }

    public static function getInstance(): Config
    {
        if (!isset(self::$instance)) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}