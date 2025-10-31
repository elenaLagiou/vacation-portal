<?php

namespace Elagiou\VacationPortal\Services;

use Elagiou\VacationPortal\Repositories\VacationRepository;

class VacationService
{
    public function __construct(protected VacationRepository $repository) {}

    public function getRequestsByUser(int $userId): array
    {
        return $this->repository->getByUserId($userId);
    }

    public function createRequest(int $userId, array $data): void
    {
        $this->repository->create([
            'user_id' => $userId,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason']
        ]);
    }

    public function deleteRequest(int $id, int $userId): void
    {
        $this->repository->deletePending($id, $userId);
    }
}
