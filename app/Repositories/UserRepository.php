<?php

namespace Elagiou\VacationPortal\Repositories;

use Elagiou\VacationPortal\Models\User;

class UserRepository
{
    protected \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all users from the database
     *
     * @return User[]
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn($row) => new User($row), $results);
    }

    /**
     * Get a single user by ID
     */
    public function getById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ? new User($data) : null;
    }

    /**
     * Create a new user
     */
    public function create(array $data): User
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (role_id, username, email, password, first_name, last_name)
            VALUES (:role_id, :username, :email, :password, :first_name, :last_name)
        ");

        $stmt->execute([
            'role_id' => $data['role_id'] ?? 3, // default employee
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
        ]);

        $data['id'] = (int)$this->pdo->lastInsertId();
        return new User($data);
    }

    /**
     * Find a user by username
     */
    public function getByUsername(string $username): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $data ? new User($data) : null;
    }
}
