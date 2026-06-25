<?php

namespace App\Controllers;

use App\Services\ComplaintService;
use App\Http\Response;
use App\Exceptions\ApiException;
use Exception;

class ComplaintController extends BaseController
{

    private ComplaintService $complaint_service;

    public function __construct(ComplaintService $complaint_service)
    {
        $this->complaint_service = $complaint_service;
    }

    public function createComplaint($user_id)
    {
        try {
            $data = $this->getRequestData();

            if (!isset($data['title']) || !isset($data['description'])) {
                throw new ApiException('Both title and description are required', 400);
            }

            $result = $this->complaint_service->createComplaint($data, $user_id);

            Response::success($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function getAllComplaints($authenticated_user)
    {
        try {
            $result = $this->complaint_service->getAllComplaints($authenticated_user);
            Response::success($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function getComplaintbyId($id, $user_id)
    {
        try {
            if (!ctype_digit($id)) {
                throw new ApiException('ID must be a positive integer', 400);
            }
            $id = (int)$id;
            $result = $this->complaint_service->getComplaintById($id, $user_id);
            Response::success($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function updateComplaint($id, $user_id)
    {
        try {
            $data = $this->getRequestData();

            if (!isset($id)) {
                throw new ApiException('ID is required for updating a complaint', 400);
            }

            $result = $this->complaint_service->updateComplaint($id, $data, $user_id);
            Response::success($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function patchComplaint($id, $user_id)
    {
        try {
            $data = $this->getRequestData();

            if (!isset($id)) {
                throw new ApiException('ID is required for updating a complaint', 400);
            }
            $result = $this->complaint_service->patchComplaint($id, $data, $user_id);
            Response::success($result);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function deleteComplaint($id, $user_id)
    {
        try {
            if (!ctype_digit($id)) {
                throw new ApiException('ID must be a positive integer', 400);
            }
            $id = (int)$id;
            $this->complaint_service->deleteComplaint($id, $user_id);
            Response::success(['message' => 'Complaint deleted successfully', 200]);
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }
}
