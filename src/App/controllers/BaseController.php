<?php

namespace App\Controllers;

use App\Services\View;

abstract class BaseController
{
    /**
     * Метод для отправки JSON-ответа.
     *
     * @param array|null $data Данные для ответа.
     * @param int $code HTTP статус-код.
     */
    protected function response(array $data = null, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_THROW_ON_ERROR);
        exit();
    }

    /**
     * Метод для отображения шаблона через View.
     *
     * @param string $template Имя шаблона.
     * @param array $data Данные для передачи в шаблон.
     */
    protected function render(string $template, array $data = []): void
    {
        $view = new View();
        $view->display($template, $data);
    }
}
