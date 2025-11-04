<?php

namespace Elagiou\VacationPortal\Controllers;

use Elagiou\VacationPortal\Services\AuthService;
use Elagiou\VacationPortal\DTO\LoginDTO;
use Elagiou\VacationPortal\Helpers\SessionFlash;
use function Elagiou\VacationPortal\Helpers\view;

class AuthController
{
    /**
     * @param AuthService $authService
     */
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Show welcome page
     *
     * @return void
     */
    public function showWelcomePage(): void
    {
        view('welcome');
    }

    /**
     * Show login page
     *
     * @return void
     */
    public function showLoginForm(): void
    {
        $error = SessionFlash::get('error');
        view('login', ['error' => $error]);
    }

    /**
     * Handle login form submission
     *
     * @param array $data
     * @return void
     */
    public function login(array $data): void
    {
        $dto = new LoginDTO($data);
        $user = $this->authService->login($dto);

        if ($user) {
            switch ($user['role_id']) {
                case 3: // Employee role
                    header('Location: /employee/home');
                    exit();
                case 2: // Manager role
                    header('Location: /manager/home');
                    exit();
                default:
                    // Handle unknown role
                    SessionFlash::set('error', 'Invalid role assigned.');
                    header('Location: /login');
                    exit();
            }
        }

        SessionFlash::set('error', 'Invalid credentials');
        header('Location: /login');
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
}
