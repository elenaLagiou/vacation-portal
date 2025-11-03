<?php

namespace Elagiou\VacationPortal\Controllers;

use Elagiou\VacationPortal\Services\AuthService;
use Elagiou\VacationPortal\Services\VacationService;

use function Elagiou\VacationPortal\Helpers\view;

class EmployeeController
{
    private AuthService $authService;
    private VacationService $vacationService;

    public function __construct(AuthService $authService, VacationService $vacationService)
    {
        $this->authService = $authService;
        $this->vacationService = $vacationService;
    }

    /**
     * Employee home page
     */
    public function home(): void
    {
        if (!$this->authService->check() || $this->authService->currentUser()['role_id'] != 3) {
            header('Location: /login');
            exit;
        }

        $currentUser = $this->authService->currentUser();
        $requests = $this->vacationService->getVacationRequestsByUser($currentUser['id']);

        view('employee/home', [
            'user' => $currentUser,
            'requests' => $requests
        ]);
    }

    /**
     * Show vacation creation form
     */
    public function createForm(): void
    {
        if (!$this->authService->check() || $this->authService->currentUser()['role_id'] != 3) {
            header('Location: /login');
            exit;
        }

        $user = (object) $this->authService->currentUser();

        view('employee/create_request', [
            'user' => $user
        ]);
    }

    /**
     * Handle vacation request creation
     */
    public function create(array $post): void
    {
        if (!$this->authService->check() || $this->authService->currentUser()['role_id'] != 3) {
            header('Location: /login');
            exit;
        }

        $user = $this->authService->currentUser();
        $post['user_id'] = $user['id'];
        $this->vacationService->createVacationRequest($post);
        header('Location: /employee/home');
        exit;
    }

    public function logout(): void
    {
        $this->authService->logout();
        header('Location: /login');
        exit;
    }
}
