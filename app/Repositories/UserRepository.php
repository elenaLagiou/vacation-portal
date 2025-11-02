<?php

namespace Elagiou\VacationPortal\Repositories;

use Elagiou\VacationPortal\DTO\UserCreationDTO;
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

    public function create(UserCreationDTO $data): User
    {
        // 1️⃣ Insert into users table
        $stmt = $this->pdo->prepare("
        INSERT INTO users (role_id, username,first_name, last_name, email, password)
        VALUES (:role_id,:username, :first_name, :last_name, :email, :password)
    ");

        $stmt->execute([
            'role_id' => $data->role_id,
            'username' => $data->username,
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'email' => $data->email,
            'password' => password_hash($data->password, PASSWORD_DEFAULT),
        ]);

        $userId = (int) $this->pdo->lastInsertId();

        if (!empty($data->details)) {
            $stmtDetails = $this->pdo->prepare("
            INSERT INTO user_details (user_id, details)
            VALUES (:user_id, :details)
        ");
            $stmtDetails->execute([
                'user_id' => $userId,
                'details' => json_encode($data->details),
            ]);
        }

        return new User([
            'id' => $userId,
            'role_id' => $data->role_id,
            'username' => $data->username,
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'email' => $data->email
        ]);
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
