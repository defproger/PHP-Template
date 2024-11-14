<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

//preload
require_once __DIR__ . '/../app/helpers/Env.php';
\App\Helpers\Env::load();
require_once __DIR__ . '/../app/services/Database.php';
require_once __DIR__ . '/../app/services/View.php';
require_once __DIR__ . '/../app/controllers/BaseController.php';

//controllers

//router
require_once __DIR__ . '/../app/routes/Router.php';
require_once __DIR__ . '/../app/routes/web.php';

