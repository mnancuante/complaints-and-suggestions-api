<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Repository\ComplaintRepository;
use App\Model\ComplaintStatus;
use App\Validator\ComplaintValidator;
use AppendIterator;

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
        $data['title'] = trim($data['title']);
        $data['description'] = trim($data['description']);
        $data['status'] = empty($data['status']) ? ComplaintStatus::OPEN : trim($data['status']);
        if (ComplaintStatus::isValid($data['status']) === false) {
            throw new ApiException('Invalid status value.', 400);
        }
        return $data;
    }

    public function createComplaint(array $data)
    {

        ComplaintValidator::validateComplaintData($data);
        $data = $this->normalizeComplaintData($data);

        if (isset($data['id'])) {
            throw new ApiException('ID should not be provided, it is auto-generated', 400);
        }

        return $this->complaint_repository->createComplaint($data);
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

    public function updateComplaint(int $id, array $data)
    {
        ComplaintValidator::validateId($id);
        $this->findComplaintOrfail($id);
        if (isset($data['id'])) {
            throw new ApiException('ID must be specified only in the URL.', 400);
        }
        ComplaintValidator::validateComplaintData($data);
        $data = $this->normalizeComplaintData($data);
        return $this->complaint_repository->updateComplaint($id, $data);
    }

    public function deleteComplaint(int $id): void
    {
        ComplaintValidator::validateId($id);
        $this->findComplaintOrfail($id);
        $this->complaint_repository->deleteComplaint($id);
    }
}
