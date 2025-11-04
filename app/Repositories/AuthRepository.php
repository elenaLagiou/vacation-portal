<?php

namespace Elagiou\VacationPortal\Repositories;

use Elagiou\VacationPortal\Models\User;

class AuthRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get a user by username for authentication
     */
    public function getByUsername(string $username): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }
}
