<?php

namespace Elagiou\VacationPortal\Repositories;

use Elagiou\VacationPortal\DTO\VacationRequestDTO;

class VacationRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all vacation requests
     */
    public function getAll(): array
    {
        $sql = "
        SELECT 
            vr.id,
            vr.user_id,
            vr.start_date,
            vr.end_date,
            vr.reason,
            vs.name AS status, 
            vr.created_at,
            vr.updated_at,
            u.first_name,
            u.last_name,
            u.email
        FROM vacation_requests vr
        LEFT JOIN vacation_status vs ON vr.status_id = vs.id
        LEFT JOIN users u ON vr.user_id = u.id
        ORDER BY vr.created_at DESC
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(fn($row) => new VacationRequestDTO($row), $rows);
    }


    /**
     * Get vacation requests for a specific user
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vacations WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Create a vacation request
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO vacations (user_id, start_date, end_date, reason, status, created_at)
            VALUES (:user_id, :start_date, :end_date, :reason, :status, :created_at)
        ");
        return $stmt->execute($data);
    }

    /**
     * Find vacation request by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vacations WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Update vacation request status
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare("UPDATE vacations SET status = :status WHERE id = :id");
        return $stmt->execute([
            'id'     => $id,
            'status' => $status
        ]);
    }

    /**
     * Delete a vacation request
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM vacations WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    public function getAllStatuses(): array
    {
        $stmt = $this->pdo->query("SELECT name FROM vacation_status ORDER BY id ASC");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
