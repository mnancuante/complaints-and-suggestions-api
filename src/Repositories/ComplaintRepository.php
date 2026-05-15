<?php
//aqui consultamos a la DB

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../../database/Database.php';

class ComplaintRepository
{

    private static mixed $conn;

    public function __construct(Database $database)
    {
        self::$conn = $database->getConnection();
    }

    public function getAllComplaints()
    {
        $sql = "SELECT * FROM complaints WHERE deleted_at IS NULL";
        $stmt = self::$conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getComplaintById(int $id)
    {
        $sql = "SELECT * FROM complaints WHERE id = :id AND deleted_at IS NULL";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createComplaint(array $data)
    {
        $sql = "INSERT INTO complaints (title, description, status) VALUES (:title, :description, :status)";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $stmt->execute();
        return "Queja agregada con éxito";
    }

    public function updateComplaint(int $id, mixed $data)
    {
        $sql = "UPDATE complaints SET title = :title, description = :description, status = :status WHERE id = :id";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $stmt->execute();
        return "Queja actualizada con éxito";
    }


    // Soft delete to mantain complaint tracking. Instead of deleting, we set a deleted_at flag and exlude those records in the GET methods. 
    public function deleteComplaint(int $id)
    {
        $sql = "UPDATE complaints SET deleted_at = NOW() WHERE id = :id";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return "Queja eliminada con éxito";
    }
}
