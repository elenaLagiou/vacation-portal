<?php

namespace Elagiou\VacationPortal\DTO;

class VacationRequestDTO
{
    public int $id;
    public int $user_id;
    public string $first_name;
    public string $last_name;
    public string $start_date;
    public string $reason;
    public string $end_date;
    public int $status_id; // store the ID
    public string $status_name; // optional, for display
    public string $created_at;

    public function __construct(array $data)
    {
        $this->id = (int)$data['id'];
        $this->user_id = (int)$data['user_id'];
        $this->first_name = $data['first_name'] ?? '';
        $this->last_name = $data['last_name'] ?? '';
        $this->reason = $data['reason'] ?? '';
        $this->start_date = $data['start_date'] ?? '';
        $this->end_date = $data['end_date'] ?? '';
        $this->status_id = (int)($data['status_id'] ?? 1);
        $this->status_name = $data['status_name'] ?? 'pending';
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
    }
}
