<?php

namespace App\Helpers;

class Env
{
    private static $data = [];

    public static function load($filePath = __DIR__ . '/../../.env')
    {
        if (!file_exists($filePath)) {
            throw new \Exception(".env file not found at path: {$filePath}");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!isset(self::$data[$name])) {
                self::$data[$name] = $value;
            }

            if (!isset($_ENV[$name])) {
                $_ENV[$name] = $value;
            }
        }
    }

    public static function get($key, $default = null)
    {
        return self::$data[$key] ?? $_ENV[$key] ?? $default;
    }
}