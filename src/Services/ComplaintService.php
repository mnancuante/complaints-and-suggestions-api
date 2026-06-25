<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Repository\ComplaintRepository;
use App\Model\ComplaintStatus;
use App\Validator\ComplaintValidator;

class ComplaintService
{

    private ComplaintRepository $complaint_repository;

    public function __construct(ComplaintRepository $complaint_repository)
    {
        $this->complaint_repository = $complaint_repository;
    }

    public function normalizeComplaintData(array $data): array
    {
        if (isset($data['title'])) {
            $data['title'] = trim($data['title']);
        }
        if (isset($data['description'])) {
            $data['description'] = trim($data['description']);
        }
        if (isset($data['status'])) {
            $data['status'] = trim($data['status']);
        }
        return $data;
    }

    public function prepareUpdate(int $complaint_id, array $data, array $authenticated_user): array
    {
        // this method will be used inside both patch and update methods to avoid code duplication
        ComplaintValidator::validateId($complaint_id);
        $this->getComplaintById($complaint_id, $authenticated_user);
        $data = $this->normalizeComplaintData($data);
        ComplaintValidator::validateComplaintData($data);
        return $data;
    }

    public function createComplaint(array $data, int $user_id)
    {

        $data = $this->normalizeComplaintData($data);
        if (empty($data['status'])) {
            $data['status'] = ComplaintStatus::OPEN;
        }
        ComplaintValidator::validateComplaintData($data);

        if (isset($data['id'])) {
            throw new ApiException('ID should not be provided, it is auto-generated', 400);
        }

        return $this->complaint_repository->createComplaint($data, $user_id);
    }

    public function getAllComplaints(array $authenticated_user)
    {
        if ($authenticated_user['role'] === 'admin') {
            return $this->complaint_repository->getAllComplaints();
        } else {
            return $this->complaint_repository->getComplaintsByUserId($authenticated_user['user_id']);
        }
    }

    public function getComplaintById(int $id, array $authenticated_user)
    {
        ComplaintValidator::validateId($id);
        $complaint = $this->complaint_repository->getComplaintById($id);
        if (!$complaint) {
            throw new ApiException('Complaint not found with ID: ' . $id, 404);
        }
        ComplaintValidator::canAccessComplaint($complaint['user_id'], $authenticated_user);
        return $complaint;
    }

    public function updateComplaint(int $id, array $data, array $authenticated_user)
    {
        ComplaintValidator::validateRequieredFields($data);
        $data = $this->prepareUpdate($id, $data, $authenticated_user);
        return $this->complaint_repository->updateComplaint($id, $data);
    }

    public function patchComplaint(int $id, array $data, array $authenticated_user)
    {
        $data = $this->prepareUpdate($id, $data, $authenticated_user);
        return $this->complaint_repository->updateComplaint($id, $data);
    }

    public function deleteComplaint(int $id, array $authenticated_user): void
    {
        ComplaintValidator::validateId($id);
        $this->getComplaintById($id, $authenticated_user['user_id']);
        $this->complaint_repository->deleteComplaint($id);
    }
}
