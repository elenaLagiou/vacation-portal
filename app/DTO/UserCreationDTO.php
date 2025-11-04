<?php

namespace Elagiou\VacationPortal\DTO;

use Respect\Validation\Validator as v;

class UserCreationDTO
{
    public string $username;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password;
    public int $role_id;
    public array $details;

    public function __construct(array $data)
    {
        $this->username = trim($data['username']);
        $this->first_name = trim($data['first_name']);
        $this->last_name = trim($data['last_name']);
        $this->email = trim($data['email']);
        $this->password = $data['password'];
        $this->role_id = (int)($data['role_id'] ?? 3);
        $this->details = $data['details'] ?? [];

        $this->validate();
    }

    private function validate(): void
    {
        v::stringType()->length(3, 50)->alnum()->check($this->username);
        v::stringType()->length(2, 50)->check($this->first_name);
        v::stringType()->length(2, 50)->check($this->last_name);
        v::email()->check($this->email);
        v::stringType()->length(6, null)->check($this->password);
        v::arrayType()->check($this->details);

        if ($this->details['employee_code'] !== "") {
            v::digit()->length(7, 7)->check($this->details['employee_code']);
        }
    }
}
