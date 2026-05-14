<?php
// este controlador recibe la solicitud del router y se encarga de llamar al repositorio para que este haga la consulta a la DB

require_once __DIR__ . '/../Services/ComplaintService.php';
require_once __DIR__ . '/../Http/Response.php';

class ComplaintController {

    private ComplaintService $complaint_service;

    public function __construct(ComplaintService $complaint_service)
    {
        $this->complaint_service = $complaint_service;
    }

    public function createComplaint () {
        try {
            //debo validar que venga $data porque yo requiero un array, si se utiliza el POST con un body vacio, $data va a ser null y eso va a generar un error al intentar acceder a las claves del array, por eso valido que venga $data y que sea un array antes de intentar acceder a sus claves
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

    public function getAllComplaints() {
        try {
            $result = $this->complaint_service->getAllComplaints();
            Response::success($result);

        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    public function getComplaintbyId($id) {
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

    public function updateComplaint() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if ($data === null || !is_array($data)) {
                throw new \Exception('Invalid input: JSON body is required');
            }

            if (!isset($data['id'])) {
                throw new \Exception('ID is required for updating a complaint');
            }
            // aqui seteo $id y luego lo elimino del array $data porque el metodo updateComplaint del repositorio espera que el id se le pase como un parametro separado del array de datos, ademas de que el id no es un campo que se deba actualizar, sino que es un identificador unico que se utiliza para localizar la complaint en la base de datos y realizar la actualizacion, por eso lo elimino del array $data para evitar confusiones o errores al momento de llamar al metodo updateComplaint del repositorio. 
            $id = $data['id'];
            unset($data['id']);

            $result = $this->complaint_service->updateComplaint($id, $data);
            Response::success($result);

        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    public function deleteComplaint($id) {
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