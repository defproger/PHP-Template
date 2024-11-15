<?php

$router->get('/', function () use ($view) {
    $view->display('index');
});
