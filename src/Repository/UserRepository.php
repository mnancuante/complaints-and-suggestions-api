<?php

namespace App\Repository;
use App\Database\Database;
use PDO;

class UserRepository {

    private mixed $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function createUser(string $email, string $password) {
        $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $user_id = $this->conn->lastInsertId();
        $user = $this->getUserById($user_id);
        return $user;
    }

    public function getUserById(int $id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getUserByEmail(string $email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }
}