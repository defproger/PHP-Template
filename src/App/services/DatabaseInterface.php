<?php

namespace App\Services;

interface DatabaseInterface
{
    public function query(string $sql, array $params = []): mixed;
    public function queryAll(string $sql, array $params = []): array;
    public function insert(string $table, array $data): int;
    public function update(string $table, array $data, int $id): void;
    public function delete(string $table, int $id): void;
    public function lastInsertId(): string;
}
