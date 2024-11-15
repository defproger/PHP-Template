<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Routes\Router;
use App\Services\View;
use App\Helpers\Env;

Env::load(__DIR__ . '/../../.env');

$view = new View();
$router = new Router($view);

require_once __DIR__ . '/../App/routes/web.php';

$router->resolve();
