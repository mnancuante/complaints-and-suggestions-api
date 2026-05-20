<?php
namespace App\Services;

use App\Repositories\ComplaintRepository;
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

        ComplaintValidator::validateComplaintData($data);
        $data = $this->normalizeComplaintData($data);

        if (isset($data['id'])) {
            throw new \Exception('ID should not be provided, it is auto-generated');
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
            throw new \Exception('ID must be specified only in the URL.');
        }
        ComplaintValidator::validateComplaintData($data);
        $data = $this->normalizeComplaintData($data);
        return $this->complaint_repository->updateComplaint($id, $data);
    }

    public function deleteComplaint(int $id) : void
    {
        ComplaintValidator::validateId($id);
        $this->findComplaintOrfail($id);
        $this->complaint_repository->deleteComplaint($id);
    }
}
