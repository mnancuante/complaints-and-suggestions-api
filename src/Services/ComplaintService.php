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

    public function prepareUpdate(int $complaint_id, array $data, int $user_id): array
    {
        // this method will be used inside both patch and update methods to avoid code duplication
        ComplaintValidator::validateId($complaint_id);
        $this->getComplaintById($complaint_id, $user_id);
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

    public function getAllComplaints(int $user_id)
    {
        return $this->complaint_repository->getAllComplaints($user_id);
    }

    public function getComplaintById(int $id, int $user_id)
    {
        ComplaintValidator::validateId($id);
        $complaint = $this->complaint_repository->getComplaintById($id);
        if (!$complaint) {
            throw new ApiException('Complaint not found with ID: ' . $id, 404);
        }
        ComplaintValidator::validateOwnership($complaint['user_id'], $user_id);
        return $complaint;
    }

    public function updateComplaint(int $id, array $data, int $user_id)
    {
        ComplaintValidator::validateRequieredFields($data);
        $data = $this->prepareUpdate($id, $data, $user_id);
        return $this->complaint_repository->updateComplaint($id, $data);
    }

    public function patchComplaint(int $id, array $data, int $user_id)
    {
        $data = $this->prepareUpdate($id, $data, $user_id);
        return $this->complaint_repository->updateComplaint($id, $data);
    }

    public function deleteComplaint(int $id, int $user_id): void
    {
        ComplaintValidator::validateId($id);
        $this->getComplaintById($id, $user_id);
        $this->complaint_repository->deleteComplaint($id);
    }
}
