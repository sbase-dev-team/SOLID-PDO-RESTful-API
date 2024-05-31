<?php

namespace App\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::loadEnv();
            try {
                $host = getenv('DB_HOST');
                $dbname = getenv('DB_NAME');
                $username = getenv('DB_USER');
                $password = getenv('DB_PASS');
                $charset = getenv('DB_CHARSET');
                $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

                self::$instance = new PDO($dsn, $username, $password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Database Connection failed: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }

    private static function loadEnv()
    {
        if (file_exists(__DIR__ . '/../../.env')) {
            $dotenv = parse_ini_file(__DIR__ . '/../../.env');
            foreach ($dotenv as $key => $value) {
                putenv("$key=$value");
            }
        }
    }
}