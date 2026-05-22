<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Database;
use App\Controllers\ComplaintController;
use App\Repository\ComplaintRepository;
use App\Services\ComplaintService;
use App\Http\Response;
use App\Repository\UserRepository;
use App\Services\AuthService;
use App\Controllers\AuthController;

$database = new Database();
$complaint_repository = new ComplaintRepository($database);
$complaint_service = new ComplaintService($complaint_repository);
$complaint_controller = new ComplaintController($complaint_service);
$user_repository = new UserRepository($database);
$auth_service = new AuthService($user_repository);
$auth_controller = new AuthController($auth_service);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace('/complaints-and-suggestions-api/api/', '', $uri);

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
            $complaint_controller->updateComplaint($id);
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
} elseif ($resource === 'register' && $method === 'POST') {
    $auth_controller->register();
} elseif ($resource === 'login' && $method === 'POST') {
    $auth_controller->login();
} else {
    Response::error("Not Found", 404);
}
