<?php

namespace App\Services;

use App\Repository\UserRepository;
use App\Validator\UserValidator;
use App\Exceptions\ApiException;
use App\Services\JWTService;

class AuthService
{

    private UserRepository $user_repository;
    private JWTService $jwt_service;

    public function __construct(UserRepository $user_repository, JWTService $jwt_service)
    {
        $this->user_repository = $user_repository;
        $this->jwt_service = $jwt_service;
    }

    private function sanitizeUser(array $user): array
    {
        if (isset($user['password'])) {
            unset($user['password']);
        }
        return $user;
    }

    public function register(array $data)
    {
        UserValidator::validateAuthData($data);
        if ($this->user_repository->getUserByEmail($data['email'])) {
            throw new ApiException('Email is already taken', 409);
        }
        $user = $this->user_repository->createUser($data['email'], password_hash($data['password'], PASSWORD_BCRYPT));
        return $this->sanitizeUser($user);
    }

    public function login(array $data)
    {
        UserValidator::validateAuthData($data);
        $user = $this->user_repository->getUserByEmail($data['email']);
        if ($user && password_verify($data['password'], $user['password'])) {
            $token = $this->jwt_service->generateToken($user);
            $user = $this->sanitizeUser($user);
            return [
                'user' => $user,
                'token' => $token
            ];
        } else {
            throw new ApiException('Invalid credentials', 401);
        }
    }
}
