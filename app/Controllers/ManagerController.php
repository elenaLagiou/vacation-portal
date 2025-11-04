<?php

namespace Elagiou\VacationPortal\Controllers;

use Elagiou\VacationPortal\Services\AuthService;
use Elagiou\VacationPortal\Services\UserService;
use Elagiou\VacationPortal\DTO\UserCreationDTO;
use Elagiou\VacationPortal\Helpers\SessionFlash;
use Elagiou\VacationPortal\Services\VacationService;
use Respect\Validation\Exceptions\ValidationException;

use function Elagiou\VacationPortal\Helpers\view;

class ManagerController
{
    /**
     * @param AuthService $authService
     * @param UserService $userService
     * @param VacationService $vacationService
     */
    public function __construct(
        protected AuthService $authService,
        protected UserService $userService,
        protected VacationService $vacationService
    ) {}

    /**
     * @return void
     */
    public function showCreateUserForm(): void
    {
        if (!$this->authService->check() || $this->authService->currentUser()['role_id'] != 2) {
            header('Location: /login');
            exit();
        }

        $user = $this->authService->currentUser();

        view('manager.create_user', [
            'user' => $user,
        ]);
    }

    /**
     * Manager home page
     *
     * @return void
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
     *
     * @param array $data
     * @return void
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
            print_r($ve->getMessage());
            SessionFlash::set('errors', [$ve->getMessage()]);
            header('Location: /manager/create-user');
            exit();
        } catch (\Throwable $e) {
            print_r($e->getMessage());
            SessionFlash::set('error', ['Failed to create user: ' . $e->getMessage()]);
            header('Location: /manager/create-user');
            exit();
        }
    }

    /**
     * @return void
     */
    public function showUpdateUserForm(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if (!$this->authService->check() || $this->authService->currentUser()['role_id'] != 2) {
            header('Location: /login');
            exit();
        }

        $user = $this->userService->getUserById($id);
        if (!$user) {
            SessionFlash::set('errors', ['User not found.']);
            header('Location: /manager/home');
            exit();
        }

        view('manager.update_user', ['user' => $user]);
    }

    /**
     * @param array $data
     * @return void
     */
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

    /**
     * @param array $data
     * @return void
     */
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
     *
     * @return void
     */
    public function logout(): void
    {
        $this->authService->logout();
        header('Location: /login');
        exit();
    }

    /**
     * List all vacation requests
     *
     * @return void
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
     *
     * @param int $id
     * @return void
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
     *
     * @param int $id
     * @return void
     */
    public function rejectRequest(int $id): void
    {
        $this->vacationService->updateStatus($id, 'rejected');

        http_response_code(200);
        echo json_encode(['message' => 'Request rejected successfully.']);
        exit;
    }
}
