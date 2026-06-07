<?php

namespace App\Repository;

use App\Repository\BaseRepository;
use PDO;

class ComplaintRepository extends BaseRepository
{

    public function getAllComplaints()
    {
        $sql = "SELECT * FROM complaints WHERE deleted_at IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getComplaintById(int $id)
    {
        $sql = "SELECT * FROM complaints WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createComplaint(array $data, int $user_id)
    {
        $sql = "INSERT INTO complaints (title, description, status, user_id) VALUES (:title, :description, :status, :user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $complaint_id = $this->conn->lastInsertId();
        $complaint = $this->getComplaintById($complaint_id);
        return $complaint;
    }

    public function updateComplaint(int $id, mixed $data)
    {
        $sql = "UPDATE complaints SET title = :title, description = :description, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $stmt->execute();
        $complaint = $this->getComplaintById($id);
        return $complaint;
    }


    // Soft delete to mantain complaint tracking. Instead of deleting, we set a deleted_at flag and exlude those records in the GET methods. 
    public function deleteComplaint(int $id): void
    {
        $sql = "UPDATE complaints SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
