<?php

namespace App\Validator;

use App\Exceptions\ApiException;
use App\Model\ComplaintStatus;

class ComplaintValidator
{
    public static function validateComplaintData(array $data): void
    {
        if (isset($data['title'])) {
            if (empty($data['title'])) {
                throw new ApiException('Title cannot be empty', 400);
            }

            if (is_numeric($data['title']) || ctype_digit($data['title'])) {
                throw new ApiException('Title cannot be only numbers', 400);
            }

            if (strlen($data['title']) > 255) {
                throw new ApiException('Title cannot exceed 255 characters', 400);
            }
        }

        if (isset($data['description'])) {
            if (empty($data['description'])) {
                throw new ApiException('Description cannot be empty', 400);
            }

            if (is_numeric($data['description']) || ctype_digit($data['description'])) {
                throw new ApiException('Description cannot be only numbers', 400);
            }

            if (strlen($data['description']) > 1000) {
                throw new ApiException('Description cannot exceed 1000 characters', 400);
            }
        }

        if (isset($data['status'])) {
            if (!ComplaintStatus::isValid($data['status'])) {
                throw new ApiException('Invalid status value.', 400);
            }
        }

        if (empty($data)) {
            throw new ApiException('At least one field must be provided for update', 400);
        }
    }

    public static function validateRequieredFields(array $data): void
    {
        if (!isset($data['title']) || !isset($data['description'])) {
            throw new ApiException('Both title and description are required', 400);
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

    public static function validateOwnership(int $user_id, int $authenticated_user_id): void
    {
        if ($user_id !== $authenticated_user_id) {
            throw new ApiException('Unauthorized access', 403);
        }
    }
}
