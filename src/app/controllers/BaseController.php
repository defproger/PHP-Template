<?php

namespace app\controllers;

class BaseController
{

    /**
     * Метод для отправки ответа
     *
     * @param array|null $data Данные для отправки в формате JSON
     * @param array|bool $errors Статус ошибок
     * @param int $code HTTP код
     */
    public function response(array $data = null, mixed $errors = false, int $code = 200): string
    {
        http_response_code($code);
        echo json_encode($data ?? ['errors' => $errors]);
        exit();
    }
}