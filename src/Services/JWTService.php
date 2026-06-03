<?php

namespace App\Services;
use Firebase\JWT\JWT;

class JWTService
{
    private string $secret_key;
    private int $expiration;

    public function __construct(string $secret_key, int $expiration)
    {
        $this->secret_key = $secret_key;
        $this->expiration = $expiration;
    }

    public function generateToken(array $user) : string
    {
        $payload = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'iat' => time(),
            'exp' => time() + 3600
        ];

        $token = JWT::encode($payload, $this->secret_key, 'HS256');
        return $token;
    }
}
