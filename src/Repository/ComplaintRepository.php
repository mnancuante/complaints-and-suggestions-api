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

    public function updateComplaint(int $complaint_id, mixed $data)
    {
        // here i will build the query dinamically based on the fields that are being updated, reutilizing for both put and patch methods.
        $fields = [];
        $params = [':id' => $complaint_id];
        if (isset($data['title'])) {
            $fields[] = 'title = :title';
            $params[':title'] = $data['title'];
        }
        if (isset($data['description'])) {
            $fields[] = 'description = :description';
            $params[':description'] = $data['description'];
        }
        if (isset($data['status'])) {
            $fields[] = 'status = :status';
            $params[':status'] = $data['status'];
        }
        $sql = "UPDATE complaints SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return $this->getComplaintById($complaint_id);
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
