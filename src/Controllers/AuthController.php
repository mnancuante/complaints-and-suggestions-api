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
            if (!isset($data['email']) || !isset($data['password'])) {
                Response::error('Email and password are required', 400);
                return;
            }
            $user = $this->auth_service->register($data['email'], $data['password']);
            Response::success($user, 201);
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    public function login()
    {
        try {
            $data = $this->getRequestData();
        if (!isset($data['email']) || !isset($data['password'])) {
            Response::error('Email and password are required', 400);
            return;
        }
        $user = $this->auth_service->login($data['email'], $data['password']);
        if ($user) {
            Response::success($user, 201);
        } else {
            Response::error('Invalid email or password', 401);
        }
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }
}
