<?php

class ComplaintValidator
{

    public static function validateComplaintData(array $data): void
    {
        if (empty($data['title']) || empty($data['description'])) {
            throw new \Exception('Title and description cannot be empty');
        }

        if (is_numeric($data['title']) || is_numeric($data['description'])) {
            throw new \Exception('Title and description cannot be only numbers');
        }

        if (ctype_digit($data['title']) || ctype_digit($data['description'])) {
            throw new \Exception('Title and description cannot be only numbers');
        }

        if (strlen($data['title']) > 255) {
            throw new \Exception('Title cannot exceed 255 characters');
        }

        if (strlen($data['description']) > 1000) {
            throw new \Exception('Description cannot exceed 1000 characters');
        }
    }

    public static function validateId(int $id): void
    {
        if (empty($id)) {
            throw new \Exception('ID cannot be empty');
        }

        if (!is_int($id) || $id <= 0) {
            throw new \Exception('ID must be a positive integer');
        }

        if (strlen((string)$id) > 11) {
            throw new \Exception('ID cannot exceed 11 digits');
        }
    }
}
