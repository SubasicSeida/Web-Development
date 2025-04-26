<?php

class Database {
    private static $host = 'localhost';
    private static $dbName = 'home_find_real_estate';
    private static $name = 'root';
    private static $password = '...';
    private static $connection = null;

    public static function connect() {
        if (self::$connection === null){
            try {
                self::$connection = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbName,
                    self::$name, 
                    self::$password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );

            } catch(PDOException $e){
                die("Connection failed : " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}

?>