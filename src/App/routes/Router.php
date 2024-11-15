<?php

namespace App\Routes;

use App\Services\View;

class Router
{
    protected array $routes = [];
    protected string $prefix = '';
    protected View $view;

    /**
     * @param View $view Экземпляр класса View для отображения шаблонов
     * @param string $prefix Префикс для маршрутов
     */
    public function __construct(View $view, string $prefix = '')
    {
        $this->view = $view;
        $this->prefix = $prefix;
    }

    /**
     * Метод для обработки GET-запросов
     *
     * @param string $url Маршрут
     * @param callable $func Функция-обработчик
     */
    public function get(string $url, callable $func): void
    {
        $this->addRoute('GET', $this->prefix . $url, $func);
    }

    /**
     * Метод для обработки POST-запросов
     *
     * @param string $url Маршрут
     * @param callable $func Функция-обработчик
     */
    public function post(string $url, callable $func): void
    {
        $this->addRoute('POST', $this->prefix . $url, $func);
    }

    /**
     * Метод для обработки PUT-запросов
     *
     * @param string $url Маршрут
     * @param callable $func Функция-обработчик
     */
    public function put(string $url, callable $func): void
    {
        $this->addRoute('PUT', $this->prefix . $url, $func);
    }

    /**
     * Метод для обработки DELETE-запросов
     *
     * @param string $url Маршрут
     * @param callable $func Функция-обработчик
     */
    public function delete(string $url, callable $func): void
    {
        $this->addRoute('DELETE', $this->prefix . $url, $func);
    }

    /**
     * Метод для добавления маршрута в роутер
     *
     * @param string $method HTTP метод
     * @param string $url Маршрут
     * @param callable $func Функция-обработчик
     */
    protected function addRoute(string $method, string $url, callable $func): void
    {
        $pattern = str_replace('/', '\/', $url);
        $pattern = preg_replace('/<(\w+)>/', '(?P<$1>[^\/]+)', $pattern);
        $this->routes[] = [
            'method' => $method,
            'pattern' => '/^' . $pattern . '$/',
            'func' => $func
        ];
    }

    /**
     * Метод для обработки входящих запросов
     */
    public function resolve(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $this->getCleanUri();

        foreach ($this->routes as $route) {
            if ($requestMethod === $route['method'] && preg_match($route['pattern'], $requestUri, $matches)) {
                if ($requestMethod === 'PUT' || $requestMethod === 'DELETE') {
                    parse_str(file_get_contents("php://input"), $inputData);
                } else {
                    $inputData = $_POST;
                }

                $route['func']($matches, $_GET, $inputData);
                return;
            }
        }

        http_response_code(404);
        $this->view->display('404');
    }

    /**
     * Метод для очистки URI от GET-параметров
     *
     * @return string Чистый URI без GET-параметров
     */
    protected function getCleanUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        return parse_url($uri, PHP_URL_PATH) ?: '/';
    }
}
