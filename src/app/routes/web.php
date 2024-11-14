<?php

use app\services\View;
use app\routes\Router;

$router = new Router();

$router->get('/', function () {
    $view = new View();
    $view->display('index');
});

$router->resolve();