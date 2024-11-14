<?php

namespace app\routes;

class Router
{
    protected $routes = [];
    protected $prefix = '';

    public function __construct($prefix = '')
    {
        $this->prefix = $prefix;
    }

    public function setPath($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Метод для обработки GET-запросов
     *
     * @param string $url
     * @param callable $func
     */
    public function get($url, $func)
    {
        $this->addRoute('GET', $this->prefix . $url, $func);
    }

    /**
     * Метод для обработки POST-запросов
     *
     * @param string $url
     * @param callable $func
     */
    public function post($url, $func)
    {
        $this->addRoute('POST', $this->prefix . $url, $func);
    }

    /**
     * Метод для обработки PUT-запросов
     *
     * @param string $url
     * @param callable $func
     */
    public function put($url, $func)
    {
        $this->addRoute('PUT', $this->prefix . $url, $func);
    }

    /**
     * Метод для обработки DELETE-запросов
     *
     * @param string $url
     * @param callable $func
     */
    public function delete($url, $func)
    {
        $this->addRoute('DELETE', $this->prefix . $url, $func);
    }

    /**
     * Метод для добавления маршрута в роутер
     *
     * @param string $method
     * @param string $url
     * @param callable $func
     */
    protected function addRoute($method, $url, $func)
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
    public function resolve()
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
                return $route['func']($matches, $_GET, $inputData);
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        exit();
    }

    /**
     * Метод для очистки URI от GET-параметров
     *
     * @return string Чистый URI без GET-параметров
     */
    protected function getCleanUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = parse_url($uri, PHP_URL_PATH);
        return $uri;
    }
}