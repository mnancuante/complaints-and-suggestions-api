<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Database;
use App\Controllers\ComplaintController;
use App\Repository\ComplaintRepository;
use App\Services\ComplaintService;
use App\Services\JWTService;
use App\Middleware\AuthMiddleware;
use App\Http\Response;
use App\Repository\UserRepository;
use App\Services\AuthService;
use App\Controllers\AuthController;

$database = new Database();
$complaint_repository = new ComplaintRepository($database);
$complaint_service = new ComplaintService($complaint_repository);
$complaint_controller = new ComplaintController($complaint_service);
$user_repository = new UserRepository($database);
$config = require __DIR__ . '/../config/config.php';
$jwt_service = new JWTService(
    $config['jwt']['secret_key'],
    $config['jwt']['expiration']
);
$auth_service = new AuthService($user_repository, $jwt_service);
$auth_controller = new AuthController($auth_service);
$auth_middleware = new AuthMiddleware($jwt_service);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace('/complaints-and-suggestions-api/api/', '', $uri);

$segments = explode('/', trim($uri, '/'));
$resource = $segments[0] ?? null;;
$id = $segments[1] ?? null;

try {

    if ($resource === 'complaints') {
        switch ($method) {
            case 'GET':
                if (isset($id)) {
                    $authenticated_user = $auth_middleware->handle();
                    $complaint_controller->getComplaintById($id, $authenticated_user['user_id']);
                } else {
                    $authenticated_user = $auth_middleware->handle();
                    $complaint_controller->getAllComplaints($authenticated_user['user_id']);
                }
                break;
            case 'POST':
                $authenticated_user = $auth_middleware->handle();
                $complaint_controller->createComplaint($authenticated_user['user_id']);
                break;

            case 'PUT':
                $authenticated_user = $auth_middleware->handle();
                $complaint_controller->updateComplaint($id, $authenticated_user['user_id']);
                break;

            case 'PATCH':
                $authenticated_user = $auth_middleware->handle();
                $complaint_controller->patchComplaint($id, $authenticated_user['user_id']);
                break;

            case 'DELETE':
                if (isset($id)) {
                    $authenticated_user = $auth_middleware->handle();
                    $complaint_controller->deleteComplaint($id, $authenticated_user['user_id']);
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
} catch (Exception $e) {
    Response::error($e->getMessage(), $e->getCode());
}
