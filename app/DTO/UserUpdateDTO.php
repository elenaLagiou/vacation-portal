<?php

namespace Elagiou\VacationPortal\DTO;

use Respect\Validation\Validator as v;

class UserUpdateDTO
{
    public int $id;
    public int $role_id;
    public string $username;
    public string $first_name;
    public string $last_name;
    public string $email;
    public ?string $password;
    public ?array $details;

    public function __construct(array $data)
    {
        $this->id = (int)$data['id'];
        $this->role_id = (int)$data['role_id'];
        $this->username = trim($data['username']);
        $this->first_name = trim($data['first_name']);
        $this->last_name = trim($data['last_name']);
        $this->email = trim($data['email']);
        $this->password = $data['password'] ?? null;
        $this->details = $data['details'] ?? null;

        $this->validate();
    }

    private function validate(): void
    {
        v::intType()->positive()->check($this->id);
        v::stringType()->length(2, 50)->check($this->first_name);
        v::stringType()->length(2, 50)->check($this->last_name);
        v::email()->check($this->email);

        if ($this->password) {
            v::stringType()->length(6, null)->check($this->password);
        }

        if (!is_null($this->details)) {
            v::arrayType()->check($this->details);
        }
    }
}
