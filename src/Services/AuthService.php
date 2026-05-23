<?php

namespace App\Services;

use App\Repository\UserRepository;

class AuthService
{

    private UserRepository $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    private function sanitizeUser(array $user): array
    {
        if (isset($user['password'])) {
            unset($user['password']);
        }
        return $user;
    }

    public function register(string $email, string $password)
    {
        $this->user_repository->createUser($email, password_hash($password, PASSWORD_BCRYPT));
        $user = $this->user_repository->getUserByEmail($email);
        return $this->sanitizeUser($user);
    }

    public function login(string $email, string $password)
    {
        $user = $this->user_repository->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $this->sanitizeUser($user);
        }
        // return null because the controller will handle the error response
        return null;
    }
}
