<?php

namespace App\Validator;

use App\Exceptions\ApiException;

class UserValidator
{
    public static function validateEmail(string $email): void
    {
        if (empty($email)) {
            throw new ApiException('Email cannot be empty', 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ApiException('Invalid email format', 400);
        }
    }

    public static function validatePassword(string $password): void
    {
        if (empty($password)) {
            throw new ApiException('Password cannot be empty', 400);
        }

        if (strlen($password) < 8) {
            throw new ApiException('Password must be at least 8 characters long', 400);
        }
    }

    public static function validateAuthData(array $data): void
    {
        if (!isset($data['email']) || !isset($data['password'])) {
            throw new ApiException('Email and password are required', 400);
        }
        self::validateEmail($data['email']);
        self::validatePassword($data['password']);
    }
}
