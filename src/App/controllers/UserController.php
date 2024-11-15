<?php

namespace App\Controllers;

use App\Services\DatabaseInterface;

class UserController extends BaseController
{
    private DatabaseInterface $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Handle user login request.
     */
    public function login(): void
    {
        $this->response(['message' => 'Login successful'], 200);
    }
}
