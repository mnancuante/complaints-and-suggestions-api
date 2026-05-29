<?php

namespace App\Validator;

use Exception;

class UserValidator
{
    public static function validateEmail(string $email): void
    {
        if (empty($email)) {
            throw new Exception('Email cannot be empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }
    }

    public static function validatePassword(string $password): void
    {
        if (empty($password)) {
            throw new Exception('Password cannot be empty');
        }

        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long');
        }
    }

    public static function validateAuthData(array $data): void
    {
        if (!isset($data['email']) || !isset($data['password'])) {
            throw new Exception('Email and password are required');
        }
        self::validateEmail($data['email']);
        self::validatePassword($data['password']);
    }
}
