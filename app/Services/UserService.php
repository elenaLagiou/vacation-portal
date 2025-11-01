<?php

namespace Elagiou\VacationPortal\Services;

use Elagiou\VacationPortal\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Get all users
     *
     * @return array
     */
    public function getAllUsers(): array
    {
        return $this->userRepo->getAll();
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return void
     */
    public function createUser(array $data): void
    {
        // Ensure password is hashed
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Default role_id to 3 (employee) if not set
        $data['role_id'] = $data['role_id'] ?? 3;

        $this->userRepo->create($data);
    }

    /**
     * Find a user by ID
     */
    public function getUserById(int $id)
    {
        return $this->userRepo->findById($id);
    }
}
