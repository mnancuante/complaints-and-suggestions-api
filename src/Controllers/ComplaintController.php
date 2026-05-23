<?php

namespace App\Controllers;

use App\Services\ComplaintService;
use App\Http\Response;
use Exception;

class ComplaintController extends BaseController
{

    private ComplaintService $complaint_service;

    public function __construct(ComplaintService $complaint_service)
    {
        $this->complaint_service = $complaint_service;
    }

    public function createComplaint()
    {
        try {
            $data = $this->getRequestData();

            if (!isset($data['title']) || !isset($data['description'])) {
                throw new \Exception('Both title and description are required');
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

    public function updateComplaint($id)
    {
        try {
            $data = $this->getRequestData();

            if (!isset($id)) {
                throw new \Exception('ID is required for updating a complaint');
            }

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
            $this->complaint_service->deleteComplaint($id);
            Response::success(['message' => 'Complaint with ID $id deleted successfully']);
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }
}
