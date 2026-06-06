<?php

namespace App\Middleware;

use App\Services\JWTService;
use App\Exceptions\ApiException;

class AuthMiddleware
{
    private JWTService $jwt_service;

    public function __construct(JWTService $jwt_service)
    {
        $this->jwt_service = $jwt_service;
    }

    public function handle()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            throw new ApiException('Authorization header is missing', 401);
        }

        $auth_header = $headers['Authorization'];
        if (strpos($auth_header, 'Bearer ') !== 0) {
            throw new ApiException('Invalid Authorization header format', 401);
        }

        $token = substr($auth_header, 7);
        return $this->jwt_service->validateToken($token);
    }
}