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

        $data->password = password_hash($data->password, PASSWORD_DEFAULT);

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
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (isset($data['details']) && is_string($data['details'])) {
            $decoded = json_decode($data['details'], true);
            $data['details'] = is_array($decoded) ? $decoded : null;
        }

        $dto = new UserUpdateDTO($data);

        $this->userRepo->update($dto);
    }


    public function deleteUser(int $id): void
    {
        $this->userRepo->delete($id);
    }
}
