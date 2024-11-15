<?php

namespace App\Services;

class View
{
    /**
     * Рендерит шаблон и возвращает его содержимое.
     *
     * @param string $template Имя файла шаблона (без расширения .php).
     * @param array $data Данные для передачи в шаблон.
     * @param callable|null $onMissingTemplate Функция для обработки случая, когда шаблон не найден.
     * @return string
     * @throws \Exception
     */
    public function render(string $template, array $data = [], callable $onMissingTemplate = null): string
    {
        $templatePath = __DIR__ . '/../../views/' . $template . '.php';

        if (!file_exists($templatePath)) {
            if (is_callable($onMissingTemplate)) {
                return $onMissingTemplate($template);
            }

            return $this->render404();
        }

        extract($data);

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

    /**
     * Отображает шаблон с выводом на экран.
     *
     * @param string $template Имя файла шаблона (без расширения .php).
     * @param array $data Данные для передачи в шаблон.
     * @param callable|null $onMissingTemplate Функция для обработки случая, когда шаблон не найден.
     */
    public function display(string $template, array $data = [], callable $onMissingTemplate = null): void
    {
        echo $this->render($template, $data, $onMissingTemplate);
    }

    /**
     * Рендерит страницу 404.
     *
     * @return string
     */
    private function render404(): string
    {
        $templatePath = __DIR__ . '/../../views/404.php';

        if (file_exists($templatePath)) {
            ob_start();
            include $templatePath;
            return ob_get_clean();
        }

        http_response_code(404);
        return "<h1>404 Not Found</h1><p>The requested page could not be found.</p>";
    }
}
