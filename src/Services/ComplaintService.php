<?php 

require_once __DIR__ . '/../Repositories/ComplaintRepository.php';
require_once __DIR__ . '/../Model/ComplaintStatus.php';
require_once __DIR__ . '/../Validator/ComplaintValidator.php';

class ComplaintService {

    private ComplaintRepository $complaint_repository;

    public function __construct(ComplaintRepository $complaint_repository)
    {
        $this->complaint_repository = $complaint_repository;
    }

    private function findComplaintOrfail(int $id) {
        $id = $this->complaint_repository->getComplaintById($id);
        if (!$id) {
            throw new \Exception('Complaint not found with ID: ' . $id);
        }
    }
   
    public function normalizeComplaintData(array $data): array {
        // aqui normalizare los datos de la complaint, por ejemplo, eliminando espacios en blanco al inicio y al final de los campos, convirtiendo el titulo a mayusculas o minusculas, etc. Esto es importante para mantener la consistencia de los datos en la base de datos y para evitar posibles problemas al momento de realizar consultas o comparaciones con los datos almacenados
        $data['title'] = trim($data['title']);
        $data['description'] = trim($data['description']);
        $data['status'] = empty($data['status']) ? ComplaintStatus::OPEN : trim($data['status']);
        if (ComplaintStatus::isValid($data['status']) === false) {
            throw new \Exception('Invalid status value.');
        }
        return $data;
    }

    public function createComplaint(array $data) {

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

    public function getAllComplaints() {
        return $this->complaint_repository->getAllComplaints();
    }

    public function getComplaintById(int $id) {
        ComplaintValidator::validateId($id);
        $this->findComplaintOrfail($id);
        return $this->complaint_repository->getComplaintById($id);
    }

    public function updateComplaint(int $id, array $data) {
        ComplaintValidator::validateId($id);
        $this->findComplaintOrfail($id);
        $data = $this->normalizeComplaintData($data);
        ComplaintValidator::validateComplaintData($data);
        return $this->complaint_repository->updateComplaint($id, $data);
    }

    public function deleteComplaint(int $id) {
        ComplaintValidator::validateId($id);
        $this->findComplaintOrfail($id);
        return $this->complaint_repository->deleteComplaint($id);
    }
}