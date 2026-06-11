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

    private function findComplaintOrfail(int $id)
    {
        $complaint = $this->complaint_repository->getComplaintById($id);
        if (!$complaint) {
            throw new ApiException('Complaint not found with ID: ' . $id, 404);
        }
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
            $data['status'] = empty($data['status']) ? ComplaintStatus::OPEN : trim($data['status']);
            if (ComplaintStatus::isValid($data['status']) === false) {
                throw new ApiException('Invalid status value.', 400);
            }
        }
        return $data;
    }

    public function prepareUpdate(int $complaint_id, array $data, int $user_id): array
    {
        // this method will be used inside both patch and update methods to avoid code duplication
        ComplaintValidator::validateId($complaint_id);
        $complaint = $this->complaint_repository->getComplaintById($complaint_id);
        $this->findComplaintOrfail($complaint_id);
        ComplaintValidator::validateOwnership($complaint['user_id'], $user_id);
        $data = $this->normalizeComplaintData($data);
        ComplaintValidator::validateComplaintData($data);
        return $data;
    }

    public function createComplaint(array $data, int $user_id)
    {

        ComplaintValidator::validateComplaintData($data);
        $data = $this->normalizeComplaintData($data);

        if (isset($data['id'])) {
            throw new ApiException('ID should not be provided, it is auto-generated', 400);
        }

        return $this->complaint_repository->createComplaint($data, $user_id);
    }

    public function getAllComplaints()
    {
        return $this->complaint_repository->getAllComplaints();
    }

    public function getComplaintById(int $id)
    {
        ComplaintValidator::validateId($id);
        $this->findComplaintOrfail($id);
        return $this->complaint_repository->getComplaintById($id);
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

    public function deleteComplaint(int $id): void
    {
        ComplaintValidator::validateId($id);
        $this->findComplaintOrfail($id);
        $this->complaint_repository->deleteComplaint($id);
    }
}
