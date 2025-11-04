<?php

namespace Elagiou\VacationPortal\Services;

use Elagiou\VacationPortal\DTO\UserCreationDTO;
use Elagiou\VacationPortal\DTO\UserUpdateDTO;
use Elagiou\VacationPortal\Models\User;
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
     * @param UserCreationDTO $data
     * @return void
     */
    public function createUser(UserCreationDTO $data): void
    {
        if (!isset($data->role_id)) {
            throw new \InvalidArgumentException("Role ID must be provided.");
        }
        // Ensure password is hashed
        if (isset($data->password)) {
            $data->password = password_hash($data->password, PASSWORD_DEFAULT);
        }

        // Default role_id to 3 (employee) if not set
        $data->role_id = $data->role_id ?? 3;

        $this->userRepo->create($data);
    }

    public function getUserById(int $id): ?UserUpdateDTO
    {
        $data = $this->userRepo->findById($id);
        return $data ? new UserUpdateDTO($data) : null;
    }
    public function updateUser(array $data): void
    {
        // ✅ Hash password if provided
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // ✅ Decode JSON string for details if needed
        if (isset($data['details']) && is_string($data['details'])) {
            $decoded = json_decode($data['details'], true);
            $data['details'] = is_array($decoded) ? $decoded : null;
        }

        // ✅ Build DTO with validation
        $dto = new UserUpdateDTO($data);

        // ✅ Delegate to repository
        $this->userRepo->update($dto);
    }


    public function deleteUser(int $id): void
    {
        $this->userRepo->delete($id);
    }
}
