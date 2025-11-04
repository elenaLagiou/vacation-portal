<?php

namespace Elagiou\VacationPortal\Repositories;

use Elagiou\VacationPortal\DTO\UserCreationDTO;
use Elagiou\VacationPortal\DTO\UserUpdateDTO;
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

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT u.*, ud.details AS details
            FROM users u
            LEFT JOIN user_details ud ON ud.user_id = u.id
            WHERE u.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) return null;

        if (!empty($row['details'])) {
            $decoded = json_decode($row['details'], true);
            $row['details'] = is_array($decoded) ? $decoded : null;
        } else {
            $row['details'] = null;
        }
        var_dump($row);

        return $row;
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



    public function update(UserUpdateDTO $dto): void
    {
        $this->pdo->beginTransaction();

        try {
            // ✅ Update main user info
            $sql = "
                UPDATE users
                SET first_name = :first_name,
                    last_name = :last_name,
                    email = :email
                " . ($dto->password ? ", password = :password" : "") . "
                WHERE id = :id
            ";

            $params = [
                'first_name' => $dto->first_name,
                'last_name'  => $dto->last_name,
                'email'      => $dto->email,
                'id'         => $dto->id,
            ];

            if ($dto->password) {
                $params['password'] = $dto->password;
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            // ✅ Update or delete details
            if (is_null($dto->details)) {
                $delete = $this->pdo->prepare("DELETE FROM user_details WHERE user_id = :id");
                $delete->execute(['id' => $dto->id]);
            } else {
                $json = json_encode($dto->details);
                $exists = $this->pdo
                    ->prepare("SELECT user_id FROM user_details WHERE user_id = :id")
                    ->execute(['id' => $dto->id]);

                $checkStmt = $this->pdo->prepare("SELECT COUNT(*) FROM user_details WHERE user_id = :id");
                $checkStmt->execute(['id' => $dto->id]);
                $hasDetails = (bool)$checkStmt->fetchColumn();

                if ($hasDetails) {
                    $update = $this->pdo->prepare("
                        UPDATE user_details SET details = :details WHERE user_id = :id
                    ");
                    $update->execute(['details' => $json, 'id' => $dto->id]);
                } else {
                    $insert = $this->pdo->prepare("
                        INSERT INTO user_details (user_id, details)
                        VALUES (:id, :details)
                    ");
                    $insert->execute(['id' => $dto->id, 'details' => $json]);
                }
            }

            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }


    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
