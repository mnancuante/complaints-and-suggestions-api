<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . '/../src/Controllers/ComplaintController.php';
require_once __DIR__ . '/../src/Services/ComplaintService.php';
require_once __DIR__ . '/../src/Repositories/ComplaintRepository.php';
require_once __DIR__ . '/../src/Model/ComplaintStatus.php';
require_once __DIR__ . '/../src/Http/Response.php';

$database = new Database();
$complaint_repository = new ComplaintRepository($database);
$complaint_service = new ComplaintService($complaint_repository);
$complaint_controller = new ComplaintController($complaint_service);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace('/crud-complaints/api/index.php', '', $uri);

$segments = explode('/', trim($uri, '/'));
$resource = $segments[0] ?? null;;
$id = $segments[1] ?? null;

if ($resource === 'complaints') {
    switch ($method) {
        case 'GET':
            if (isset($id)) {
                $complaint_controller->getComplaintById($id);
            } else {
                $complaint_controller->getAllComplaints();
            }
            break;
        case 'POST':
            $complaint_controller->createComplaint();
            break;

        case 'PUT':
            $complaint_controller->updateComplaint();
            break;

        case 'DELETE':
            if (isset($id)) {
                $complaint_controller->deleteComplaint($id);
            } else {
                Response::error('ID is required for DELETE method', 400);
            }
            break;
        default:
            Response::error("Method Not Allowed", 405);
    }
} else {
    Response::error("Not Found", 404);
}
