<?php

namespace App\Container;

use App\Database\Database;
use App\Repository\UserRepository;
use App\Repository\ComplaintRepository;
use App\Middleware\AuthMiddleware;
use App\Services\JWTService;
use App\Services\AuthService;
use App\Services\ComplaintService;
use App\Controllers\AuthController;
use App\Controllers\ComplaintController;

class Container
{
    private array $instances = [];
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
    public function get(string $service)
    {
        if (isset($this->instances[$service])) {
            return $this->instances[$service];
        }

        $instance = $this->create($service);

        $this->instances[$service] = $instance;

        return $instance;
    }

    public function create(string $service)
    {
        switch ($service) {
            case (AuthController::class):
                return new AuthController($this->get(AuthService::class));
            case (AuthMiddleware::class):
                return new AuthMiddleware($this->get(JWTService::class));
            case (ComplaintController::class):
                return new ComplaintController($this->get(ComplaintService::class));
            case (ComplaintService::class):
                return new ComplaintService($this->get(ComplaintRepository::class));
            case (JWTService::class):
                return new JWTService($this->config['jwt']['secret_key'], $this->config['jwt']['expiration']);
            case (AuthService::class):
                return new AuthService($this->get(UserRepository::class), $this->get(JWTService::class));
            case (ComplaintRepository::class):
                return new ComplaintRepository($this->get(Database::class));
            case (UserRepository::class):
                return new UserRepository($this->get(Database::class));
            case (Database::class):
                return new Database($this->config);
            default:
                throw new \RuntimeException("Service {$service} not found.");
        }
    }
}
