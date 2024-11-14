<?php

namespace app\controllers;

use app\services\Database;

class UserController extends BaseController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function login()
    {
        $this->response(['message' => 'Login successful']);
    }
}