<?php
// Strict typing for production-level PHP
declare(strict_types=1);

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            // In a real setup, you'd load the .env file here using a library like vlucas/phpdotenv
            $host = getenv('DB_HOST') ?: 'localhost';
            $db = getenv('DB_NAME') ?: 'kfinance_db';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            // Production PDO options
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return arrays, not objects
                PDO::ATTR_EMULATE_PREPARES => false,                  // True prepared statements for maximum security
            ];

            try {
                self::$connection = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // NEVER echo $e->getMessage() in production, it reveals DB structure to hackers
                error_log("Database Connection Failed: " . $e->getMessage());
                die("A system error occurred. Please try again later.");
            }
        }
        return self::$connection;
    }
}
?>