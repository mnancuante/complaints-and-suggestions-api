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

    public function register(string $email, string $password)
    {
        $this->user_repository->createUser($email, password_hash($password, PASSWORD_BCRYPT));
    }

    public function login(string $email, string $password)
    {
        $user = $this->user_repository->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        // return null because the controller will handle the error response
        return null;
    }
}
