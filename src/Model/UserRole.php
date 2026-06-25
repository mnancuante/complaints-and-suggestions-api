<?php

namespace App\Model;

class UserRole
{
    const ADMIN = 'admin';
    const USER = 'user';

    public static function isValid(string $role) : bool
    {
       $valid_statuses = [
        self::ADMIN,
        self::USER
       ];

       return in_array($role, $valid_statuses);

    }
}