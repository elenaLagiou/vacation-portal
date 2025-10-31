<?php

namespace Elagiou\VacationPortal\Services;

use Elagiou\VacationPortal\Repositories\AuthRepository;
use Elagiou\VacationPortal\DTO\LoginDTO;

class AuthService
{
    private AuthRepository $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Attempt login with username/password
     *
     * @param LoginDTO $dto
     * @return array|null Session user data or null if failed
     */
    public function login(LoginDTO $dto): ?array
    {
        $user = $this->repository->getByUsername($dto->username);

        if (!$user) return null;

        if (password_verify($dto->password, $user->password)) {
            // Start session if not already started
            if (session_status() === PHP_SESSION_NONE) session_start();

            $_SESSION['user'] = [
                'id' => $user->id,
                'username' => $user->username,
                'role_id' => $user->role_id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name
            ];

            return $_SESSION['user'];
        }

        return null;
    }

    /**
     * Logout current user
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
    }

    /**
     * Get the currently authenticated user from session
     */
    public function currentUser(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['user'] ?? null;
    }

    /**
     * Check if a user is authenticated
     */
    public function check(): bool
    {
        return $this->currentUser() !== null;
    }
}
