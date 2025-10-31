<?php

namespace Elagiou\VacationPortal\Repositories;

use Elagiou\VacationPortal\Models\User;

class UserRepository
{
    public function __construct(private \PDO $pdo) {}

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        $rows = $stmt->fetchAll();
        return array_map(fn($r) => new User($r), $rows);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    public function create(array $data): User
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (role_id, first_name, last_name, email, employee_code, password)
            VALUES (:role_id, :first_name, :last_name, :email, :employee_code, :password)
        ");
        $stmt->execute([
            'role_id' => $data['role_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'employee_code' => $data['employee_code'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
        $data['id'] = (int)$this->pdo->lastInsertId();
        return new User($data);
    }

    public function update(array $data): bool
    {
        $fields = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $fields['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
        $fields['id'] = $data['id'];

        $stmt = $this->pdo->prepare("UPDATE users SET $set WHERE id = :id");
        return $stmt->execute($fields);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
