<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Exceptions\ApiException;

class JWTService
{
    private string $secret_key;
    private int $expiration;

    public function __construct(string $secret_key, int $expiration)
    {
        $this->secret_key = $secret_key;
        $this->expiration = $expiration;
    }

    public function generateToken(array $user): string
    {
        $payload = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'iat' => time(),
            'exp' => time() + 3600
        ];

        $token = JWT::encode($payload, $this->secret_key, 'HS256');
        return $token;
    }

    public function validateToken(string $token): array
    {

        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            throw new ApiException('Invalid or expired token: ' . $e->getMessage(), 401);
        }
    }
}
