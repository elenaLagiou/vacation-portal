<?php

namespace Elagiou\VacationPortal\Repositories;

use PDO;

class VacationRepository
{
    public function __construct(protected PDO $pdo)
    {
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vacation_requests WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO vacation_requests (user_id, start_date, end_date, reason, status, created_at)
            VALUES (:user_id, :start_date, :end_date, :reason, 'pending', NOW())
        ");
        $stmt->execute($data);
    }

    public function deletePending(int $id, int $userId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM vacation_requests
            WHERE id = :id AND user_id = :user_id AND status = 'pending'
        ");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }
}
