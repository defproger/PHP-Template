<?php

namespace App\Helpers;

class Env
{
    /**
     * Загружает переменные окружения из .env файла в глобальный массив $_ENV.
     *
     * @param string $filePath Путь к .env файлу.
     * @throws \Exception Если файл не найден.
     */
    public static function load(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Файл .env не найден: {$filePath}");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            $key = trim($key);
            $value = trim($value);

            if (isset($_ENV[$key])) {
                continue;
            }

            $_ENV[$key] = $value;
        }
    }
}
