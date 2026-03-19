<?php
declare(strict_types=1);

/**
 * Database — PDO singleton with utf8mb4 charset.
 */
class Database
{
    private static ?PDO $instance = null;

    private string $host     = 'localhost';
    private string $username = 'root';
    private string $password = 'mysql';
    private string $dbname   = 'vcargo';
    private string $charset  = 'utf8mb4';

    public function connect(): PDO
    {
        if (self::$instance === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            self::$instance = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$instance;
    }
}
