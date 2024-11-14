<?php

namespace App\Services;

use App\Helpers\Env;
use PDO;
use PDOException;
use App\Helpers\Config;

class Database
{
    private static $instance = null;
    private $dbh;

    private function __construct()
    {
        $config = [
            'host' => Env::get('DB_HOST'),
            'port' => Env::get('DB_PORT'),
            'dbname' => Env::get('DB_NAME'),
            'user' => Env::get('DB_USER'),
            'password' => Env::get('DB_PASSWORD')
        ];

        $this->connect($config);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect($config)
    {
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

    public function query($sql, $params = [])
    {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($this->normalizeParams($params));
        return $stmt->fetch();
    }

    public function queryAll($sql, $params = [])
    {
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($this->normalizeParams($params));
        return $stmt->fetchAll();
    }

    public function insert($table, $data)
    {
        $fields = array_keys($data);
        $placeholders = ":" . implode(", :", $fields);
        $sql = "INSERT INTO `{$table}` (" . implode(", ", $fields) . ") VALUES ({$placeholders})";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute($data);
    }

    public function update($table, $id, $data)
    {
        $fields = array_keys($data);
        $set = implode(" = ?, ", $fields) . " = ?";
        $sql = "UPDATE `{$table}` SET {$set} WHERE id = ?";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(array_merge(array_values($data), [$id]));
    }

    public function delete($table, $id)
    {
        $stmt = $this->dbh->prepare("DELETE FROM `{$table}` WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function lastInsertId($name = null)
    {
        return $this->dbh->lastInsertId($name);
    }

    private function normalizeParams($params)
    {
        $normalized = [];
        foreach ($params as $key => $value) {
            $normalized[":" . $key] = $value;
        }
        return $normalized;
    }

    public static function cryptPass($password)
    {
        return hash('sha256', $password . 'Fibonacci');
    }
}