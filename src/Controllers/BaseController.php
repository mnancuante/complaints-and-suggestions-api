<?php

namespace App\Controllers;

class BaseController {

    protected function getRequestData() :array {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data === null || !is_array($data)) {
            throw new \Exception('Invalid input: JSON body is required');
        }
        return $data;
    }
}