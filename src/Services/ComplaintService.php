<?php

require_once __DIR__ . '/../Repositories/ComplaintRepository.php';
require_once __DIR__ . '/../Model/ComplaintStatus.php';
require_once __DIR__ . '/../Validator/ComplaintValidator.php';

class ComplaintService
{

    private ComplaintRepository $complaint_repository;

    public function __construct(ComplaintRepository $complaint_repository)
    {
        $this->complaint_repository = $complaint_repository;
    }

    private function findComplaintOrfail(int $id)
    {
        $id = $this->complaint_repository->getComplaintById($id);
        if (!$id) {
            throw new \Exception('Complaint not found with ID: ' . $id);
        }
    }

    public function normalizeComplaintData(array $data): array
    {
        $data['title'] = trim($data['title']);
        $data['description'] = trim($data['description']);
        $data['status'] = empty($data['status']) ? ComplaintStatus::OPEN : trim($data['status']);
        if (ComplaintStatus::isValid($data['status']) === false) {
            throw new \Exception('Invalid status value.');
        }
        return $data;
    }

    public function createComplaint(array $data)
    {

        $data = $this->normalizeComplaintData($data);

        if (isset($data['id'])) {
            throw new \Exception('ID should not be provided, it is auto-generated');
        }

        if (!isset($data['title']) || !isset($data['description'])) {
            throw new \Exception('Both title and description are required');
        }

        ComplaintValidator::validateComplaintData($data);

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
        $data = $this->normalizeComplaintData($data);
        ComplaintValidator::validateComplaintData($data);
        return $this->complaint_repository->updateComplaint($id, $data);
    }

    public function deleteComplaint(int $id)
    {
        ComplaintValidator::validateId($id);
        $this->findComplaintOrfail($id);
        return $this->complaint_repository->deleteComplaint($id);
    }
}
