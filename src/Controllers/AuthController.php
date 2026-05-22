<?php

namespace App\Controllers;
use App\Services\AuthService;
use App\Http\Response;

class AuthController {

    private AuthService $auth_service;

    public function __construct(AuthService $auth_service)
    {
        $this->auth_service = $auth_service;
    }

    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['email']) || !isset($data['password'])) {
            Response::error('Email and password are required', 400);
            return;
        }
        $user = $this->auth_service->register($data['email'], $data['password']);
        Response::success($user, 201);
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
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
    }
}