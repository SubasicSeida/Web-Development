<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config {
    public static function DB_NAME(){
        return 'home_find_real_estate';
    }

    public static function DB_PORT(){
        return 3306;
    }

    public static function DB_USER(){
        return $_ENV["DB_USER"] ?? null;
    }

    public static function DB_PASSWORD(){
        return $_ENV["DB_PASSWORD"] ?? null;
    }

    public static function DB_HOST()
    {
        return '127.0.0.1';
    }

    public static function JWT_SECRET() {
        return $_ENV["JWT_SECRET"] ?? null;
    }
}

?>