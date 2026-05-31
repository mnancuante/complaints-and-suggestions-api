<?php

namespace App\Validator;

use App\Exceptions\ApiException;

class ComplaintValidator
{

    public static function validateComplaintData(array $data): void
    {
        if (empty($data['title']) || empty($data['description'])) {
            throw new ApiException('Title and description cannot be empty', 400);
        }

        if (is_numeric($data['title']) || is_numeric($data['description'])) {
            throw new ApiException('Title and description cannot be only numbers', 400);
        }

        if (ctype_digit($data['title']) || ctype_digit($data['description'])) {
            throw new ApiException('Title and description cannot be only numbers', 400);
        }

        if (strlen($data['title']) > 255) {
            throw new ApiException('Title cannot exceed 255 characters', 400);
        }

        if (strlen($data['description']) > 1000) {
            throw new ApiException('Description cannot exceed 1000 characters', 400);
        }
    }

    public static function validateId(int $id): void
    {
        if (empty($id)) {
            throw new ApiException('ID cannot be empty', 400);
        }

        if (!is_int($id) || $id <= 0) {
            throw new ApiException('ID must be a positive integer', 400);
        }

        if (strlen((string)$id) > 11) {
            throw new ApiException('ID cannot exceed 11 digits', 400);
        }
    }
}
