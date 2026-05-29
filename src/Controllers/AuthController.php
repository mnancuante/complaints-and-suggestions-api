<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Http\Response;

class AuthController extends BaseController
{

    private AuthService $auth_service;

    public function __construct(AuthService $auth_service)
    {
        $this->auth_service = $auth_service;
    }

    public function register()
    {
        try {
            $data = $this->getRequestData();
            $user = $this->auth_service->register($data);
            Response::success($user, 201);
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    public function login()
    {
        try {
            $data = $this->getRequestData();
            $user = $this->auth_service->login($data);
            Response::success($user, 201);
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
}
