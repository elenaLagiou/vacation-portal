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
        $stmt = $this->pdo->prepare(
            "SELECT 
            vr.id, 
            vr.user_id, 
            u.first_name, 
            u.last_name,
            vr.start_date, 
            vr.end_date, 
            vr.reason, 
            vr.created_at,
            vr.status_id,
            vs.name AS status_name,
            vr.created_at
         FROM vacation_requests vr
         LEFT JOIN users u ON u.id = vr.user_id
         LEFT JOIN vacation_status vs ON vr.status_id = vs.id
         WHERE vr.user_id = :user_id
         ORDER BY vr.created_at DESC"
        );

        $stmt->execute(['user_id' => $userId]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $requests = [];
        foreach ($rows as $row) {
            $requests[] = new VacationRequestDTO($row);
        }

        return $requests;
    }

    /**
     * Create a vacation request
     */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO vacation_requests (user_id, start_date, end_date, reason, status_id, created_at)
            VALUES (:user_id, :start_date, :end_date, :reason, :status_id, :created_at)
        ");
        return $stmt->execute($data);
    }

    /**
     * Find vacation request by ID
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vacation_requests WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Update vacation request status
     */
    public function updateStatus(int $id, string $name): bool
    {
        // 1. Find the corresponding status_id by its name
        $stmt = $this->pdo->prepare("SELECT id FROM vacation_status WHERE name = :name LIMIT 1");
        $stmt->execute(['name' => $name]);
        $status = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$status || !isset($status['id'])) {
            throw new \RuntimeException("Invalid status name: {$name}");
        }

        $status_id = (int) $status['id'];

        // 2. Update the vacation request record
        $stmt = $this->pdo->prepare("UPDATE vacation_requests SET status_id = :status_id WHERE id = :id");
        return $stmt->execute([
            'status_id' => $status_id,
            'id'        => $id
        ]);
    }


    /**
     * Delete a vacation request
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM vacation_requests WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    public function getAllStatuses(): array
    {
        $stmt = $this->pdo->query("SELECT name FROM vacation_status ORDER BY id ASC");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
