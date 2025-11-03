<?php

namespace Elagiou\VacationPortal\Controllers;

use Elagiou\VacationPortal\Services\AuthService;
use Elagiou\VacationPortal\Services\UserService;
use Elagiou\VacationPortal\DTO\LoginDTO;
use Elagiou\VacationPortal\DTO\UserCreationDTO;
use Elagiou\VacationPortal\Helpers\SessionFlash;
use Elagiou\VacationPortal\Services\VacationService;
use Respect\Validation\Exceptions\ValidationException;

use function Elagiou\VacationPortal\Helpers\view;

class ManagerController
{

    public function __construct(
        protected AuthService $authService,
        protected UserService $userService,
        protected VacationService $vacationService
    ) {}

    /**
     * Show login page
     */
    public function showLoginForm(): void
    {
        $error = $_GET['error'] ?? null;
        view('login', ['error' => $error]);
    }

    public function showCreateUserForm(): void
    {
        if (!$this->authService->check() || $this->authService->currentUser()['role_id'] != 2) {
            header('Location: /login');
            exit();
        }

        view('manager.create_user');
    }

    /**
     * Manager home page
     */
    public function home(): void
    {
        if (!$this->authService->check() || $this->authService->currentUser()['role_id'] != 2) {
            header('Location: /login');
            exit();
        }

        $currentUser = $this->authService->currentUser();
        $users = $this->userService->getAllUsers();

        view('manager.home', [
            'currentUser' => $currentUser,
            'users' => $users
        ]);
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): void
    {
        try {
            $dto = new UserCreationDTO($data);
            $this->userService->createUser($dto);

            SessionFlash::set('success', 'User created successfully!');
            header('Location: /manager/home');
            exit();
        } catch (ValidationException $ve) {
            SessionFlash::set('errors', [$ve->getMessage()]);
            header('Location: /manager/create-user');
            exit();
        } catch (\Throwable $e) {
            SessionFlash::set('error', ['Failed to create user: ' . $e->getMessage()]);
            header('Location: /manager/create-user');
            exit();
        }
    }
    public function showUpdateUserForm(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if (!$this->authService->check() || $this->authService->currentUser()['role_id'] != 2) {
            header('Location: /login');
            exit();
        }

        $user = $this->userService->getUserById($id);
        if (!$user) {
            SessionFlash::set('error', ['User not found.']);
            header('Location: /manager/home');
            exit();
        }

        view('manager.update_user', ['user' => $user]);
    }

    public function updateUser(array $data): void
    {
        try {
            $this->userService->updateUser($data);
            SessionFlash::set('success', 'User updated successfully!');
            header('Location: /manager/home');
            exit();
        } catch (\Throwable $e) {
            SessionFlash::set('errors', ['Failed to update user: ' . $e->getMessage()]);
            header('Location: /manager/update-user?id=' . $data['id']);
            exit();
        }
    }

    public function deleteUser(array $data): void
    {
        try {
            $this->userService->deleteUser((int)$data['id']);
            SessionFlash::set('success', 'User deleted successfully!');
        } catch (\Throwable $e) {
            SessionFlash::set('errors', ['Failed to delete user: ' . $e->getMessage()]);
        }

        header('Location: /manager/home');
        exit();
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        $this->authService->logout();
        header('Location: /login');
        exit();
    }
    /**
     * List all vacation requests
     */
    public function listRequests(): void
    {
        $requests = $this->vacationService->getAllVacationRequests();
        $statuses = $this->vacationService->getAllStatuses();

        view('manager.requests', [
            'requests' => $requests,
            'statuses' => $statuses
        ]);
    }


    /**
     * Approve a vacation request
     */
    public function approveRequest(int $id): void
    {
        $this->vacationService->updateStatus($id, 'approved');
        http_response_code(200);
        echo json_encode(['message' => 'Request approved successfully.']);
        exit;
    }

    /**
     * Reject a vacation request
     */
    public function rejectRequest(int $id): void
    {
        $this->vacationService->updateStatus($id, 'rejected');

        http_response_code(200);
        echo json_encode(['message' => 'Request rejected successfully.']);
        exit;
    }
}
