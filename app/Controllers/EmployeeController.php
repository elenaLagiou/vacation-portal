<?php

namespace Elagiou\VacationPortal\Controllers;

use Elagiou\VacationPortal\Services\AuthService;
use Elagiou\VacationPortal\Services\VacationService;
use Elagiou\VacationPortal\Helpers\SessionFlash;

use function Elagiou\VacationPortal\Helpers\view;

class EmployeeController
{
    /**
     * @param AuthService $authService
     * @param VacationService $vacationService
     */
    public function __construct(
        protected AuthService $authService,
        protected VacationService $vacationService
    ) {
    }

    /**
     * Employee home page
     *
     * @return void
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
     *
     * @return void
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
     *
     * @param array $post
     * @return void
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

    /**
     * Delete a vacation request (employee)
     *
     * @param int $id
     * @return void
     */
    public function deleteRequest(int $id): void
    {
        if (!$this->authService->check() || $this->authService->currentUser()['role_id'] != 3) {
            header('Location: /login');
            exit;
        }

        $user = $this->authService->currentUser();

        try {
            $this->vacationService->delete($id, $user['id']);
            SessionFlash::set('success', 'Vacation request deleted successfully.');
        } catch (\Throwable $e) {
            SessionFlash::set('errors', ['Failed to delete request: ' . $e->getMessage()]);
        }

        header('Location: /employee/home');
        exit;
    }
}
