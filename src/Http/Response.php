<?php

class Response
{
    private static function send(array $response, int $status_code): void
    {
        header("Content-Type: application/json");
        http_response_code($status_code);
        echo json_encode($response);
    }
    public static function success($data, int $status_code = 200): void
    {
        header("Content-Type: application/json");
        http_response_code($status_code);
        self::send([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public static function error(string $message, int $status_code = 400): void
    {
        header("Content-Type: application/json");
        http_response_code($status_code);
        self::send([
            'success' => false,
            'message' => $message
        ], 400);
    }
}
