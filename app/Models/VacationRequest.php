<?php

namespace Elagiou\VacationPortal\Models;

class VacationRequest
{
    public int $id;
    public int $user_id;
    public string $start_date;
    public string $end_date;
    public ?string $reason;
    public string $status;
    public string $created_at;

    public function __construct(array $data)
    {
        $this->id = (int)($data['id'] ?? 0);
        $this->user_id = (int)($data['user_id'] ?? 0);
        $this->start_date = $data['start_date'] ?? '';
        $this->end_date = $data['end_date'] ?? '';
        $this->reason = $data['reason'] ?? null;
        $this->status = $data['status'] ?? 'pending';
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
    }

    /**
     * Helper: check if the vacation is still pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Helper: check if the vacation was approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Helper: check if the vacation was rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Format the date range for easy display in views
     */
    public function dateRange(): string
    {
        return "{$this->start_date} → {$this->end_date}";
    }
}
