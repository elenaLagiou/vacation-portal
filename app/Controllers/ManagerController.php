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
    private AuthService $authService;
    private UserService $userService;
    private VacationService $vacationService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

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
     * Handle login form submission
     */
    public function login(array $data): void
    {
        $dto = new LoginDTO($data);
        $user = $this->authService->login($dto);

        if ($user && $user['role_id'] == 2) { // Manager role
            header('Location: /manager/home');
            exit();
        }

        header('Location: /login?error=Invalid credentials or not a manager');
        exit();
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
            SessionFlash::set('errors', $ve->getMessage());
            header('Location: /manager/create-user');
            exit();
        } catch (\Throwable $e) {
            SessionFlash::set('error', 'Failed to create user: ' . $e->getMessage());
            header('Location: /manager/create-user');
            exit();
        }
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
        require __DIR__ . '/../../resources/views/manager/requests.php';
    }

    /**
     * Approve a vacation request
     */
    public function approveRequest(int $id): void
    {
        $this->vacationService->updateStatus($id, 'approved');
        header('Location: /manager/requests');
        exit;
    }

    /**
     * Reject a vacation request
     */
    public function rejectRequest(int $id): void
    {
        $this->vacationService->updateStatus($id, 'rejected');
        header('Location: /manager/requests');
        exit;
    }
}
