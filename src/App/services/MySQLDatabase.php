<?php

namespace App\Services;

use PDO;
use PDOException;
use App\Helpers\Env;

class MySQLDatabase implements DatabaseInterface
{
    private static ?MySQLDatabase $instance = null;
    private PDO $dbh;

    private function __construct()
    {
        $this->connect();
    }

    public static function getInstance(): MySQLDatabase
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect(): void
    {
        $config = [
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'dbname' => $_ENV['DB_NAME'] ?? 'base',
            'user' => $_ENV['DB_USER'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? ''
        ];

        try {
            $this->dbh = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8;port={$config['port']}",
                $config['user'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function query(string $sql, array $params = []): mixed
    {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function queryAll(string $sql, array $params = []): array
    {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function insert(string $table, array $data): int
    {
        $fields = array_keys($data);
        $placeholders = ':' . implode(', :', $fields);
        $sql = "INSERT INTO `{$table}` (" . implode(', ', $fields) . ") VALUES ({$placeholders})";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($data);
        return (int)$this->dbh->lastInsertId();
    }

    public function update(string $table, array $data, int $id): void
    {
        $fields = implode(', ', array_map(fn($key) => "`$key` = :$key", array_keys($data)));
        $sql = "UPDATE `{$table}` SET {$fields} WHERE id = :id";
        $stmt = $this->dbh->prepare($sql);
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function delete(string $table, int $id): void
    {
        $stmt = $this->dbh->prepare("DELETE FROM `{$table}` WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function lastInsertId(): string
    {
        return $this->dbh->lastInsertId();
    }
}
