<?php

class ComplaintValidator {

    public static function validateComplaintData(array $data): void {
        // aqui tendremos el o los metodos que validen las complaints, por ejemplo, validar que el titulo y la descripcion no esten vacios, que no sean solo numeros, que no excedan cierta cantidad de caracteres, etc. Si alguna de las validaciones falla, se lanzara una excepcion con un mensaje descriptivo del error
        
        if (empty($data['title']) || empty($data['description'])) {
            throw new \Exception('Title and description cannot be empty');
        }

        // valido que el titulo y la descripcion no sean solo numeros, porque eso no tiene sentido en el contexto de una queja, ademas de que podria ser un indicio de que se esta intentando inyectar codigo malicioso o simplemente de que el usuario no esta proporcionando informacion valiosa para resolver su problema
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

    public static function validateId(int $id): void {
        // aqui validare que $id no este vacio, que sea un entero positivo y que no exceda cierta cantidad de digitos para evitar posibles ataques de inyeccion SQL o errores al intentar convertir un string a int
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