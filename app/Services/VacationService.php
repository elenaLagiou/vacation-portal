<?php

namespace Elagiou\VacationPortal\Services;

use Elagiou\VacationPortal\Repositories\VacationRepository;

class VacationService
{
    private VacationRepository $vacationRepository;

    public function __construct(VacationRepository $vacationRepository)
    {
        $this->vacationRepository = $vacationRepository;
    }

    /**
     * Get all vacation requests (for managers)
     */
    public function getAllVacationRequests(): array
    {
        return $this->vacationRepository->getAll();
    }

    /**
     * Get all vacation requests for a specific user (for employees)
     */
    public function getVacationRequestsByUser(int $userId): array
    {
        return $this->vacationRepository->getByUserId($userId);
    }

    /**
     * Create a new vacation request
     */
    public function createVacationRequest(array $data): void
    {
        // Validate simple fields
        if (empty($data['user_id']) || empty($data['start_date']) || empty($data['end_date'])) {
            throw new \InvalidArgumentException("Missing required vacation request fields");
        }

        $this->vacationRepository->create([
            'user_id'     => $data['user_id'],
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            'reason'      => $data['reason'] ?? '',
            'status'      => 'pending',
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update vacation status (approve / reject)
     */
    public function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, ['approved', 'rejected', 'pending'])) {
            throw new \InvalidArgumentException("Invalid status value");
        }

        return $this->vacationRepository->updateStatus($id, $status);
    }

    /**
     * Delete a vacation request (for employees)
     */
    public function deleteVacationRequest(int $id, int $userId): bool
    {
        $request = $this->vacationRepository->findById($id);
        if (!$request || $request['user_id'] !== $userId || $request['status'] !== 'pending') {
            throw new \RuntimeException("Cannot delete this vacation request");
        }

        return $this->vacationRepository->delete($id);
    }
    public function getAllStatuses(): array
    {
        return $this->vacationRepository->getAllStatuses();
    }
}
