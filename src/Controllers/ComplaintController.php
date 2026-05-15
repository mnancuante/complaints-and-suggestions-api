<?php
require_once __DIR__ . '/../Services/ComplaintService.php';
require_once __DIR__ . '/../Http/Response.php';

class ComplaintController
{

    private ComplaintService $complaint_service;

    public function __construct(ComplaintService $complaint_service)
    {
        $this->complaint_service = $complaint_service;
    }

    public function createComplaint()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data === null || !is_array($data)) {
                throw new \Exception('Invalid input: JSON body is required');
            }

            $result = $this->complaint_service->createComplaint($data);

            Response::success($result);
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    public function getAllComplaints()
    {
        try {
            $result = $this->complaint_service->getAllComplaints();
            Response::success($result);
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    public function getComplaintbyId($id)
    {
        try {
            if (!ctype_digit($id)) {
                throw new \Exception('ID must be a positive integer');
            }
            $id = (int)$id;
            $result = $this->complaint_service->getComplaintById($id);
            Response::success($result);
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    public function updateComplaint()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data === null || !is_array($data)) {
                throw new \Exception('Invalid input: JSON body is required');
            }

            if (!isset($data['id'])) {
                throw new \Exception('ID is required for updating a complaint');
            }
            // Set id and remove it from data array to avoid confusion when passing it to the service
            $id = $data['id'];
            unset($data['id']);

            $result = $this->complaint_service->updateComplaint($id, $data);
            Response::success($result);
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    public function deleteComplaint($id)
    {
        try {
            if (!ctype_digit($id)) {
                throw new \Exception('ID must be a positive integer');
            }
            $id = (int)$id;
            $result = $this->complaint_service->deleteComplaint($id);
            Response::success($result);
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }
}
