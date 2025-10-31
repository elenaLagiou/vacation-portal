<?php

namespace Elagiou\VacationPortal\Middleware;

use Elagiou\VacationPortal\Services\AuthService;

class AuthMiddleware
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(): bool
    {
        if (!$this->authService->check()) {
            header('Location: /login');
            return false;
        }
        return true;
    }

    public function managerOnly(): bool
    {
        $user = $this->authService->currentUser();
        if (!$user || $user['role_id'] != 2) {
            header('HTTP/1.1 403 Forbidden');
            echo 'Access denied';
            return false;
        }
        return true;
    }
}
