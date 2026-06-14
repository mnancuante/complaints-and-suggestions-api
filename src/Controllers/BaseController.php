<?php

namespace App\Controllers;

use App\Exceptions\ApiException;
use App\Http\Response;

class BaseController
{

    protected function getRequestData(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null || !is_array($data)) {
            throw new \Exception('Invalid input: JSON body is required');
        }
        return $data;
    }

    protected function handleException(\Throwable $e)
    {
        if ($e instanceof ApiException) {
            Response::error(
                $e->getMessage(),
                $e->getCode()
            );
            return;
        }
        Response::error($e->getMessage(), 500);
    }
}
