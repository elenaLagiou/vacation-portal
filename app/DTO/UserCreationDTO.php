<?php

namespace Elagiou\VacationPortal\DTO;

use Respect\Validation\Validator as v;

class UserCreationDTO
{
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $employee_code;
    public string $password;
    public int $role_id;

    public function __construct(array $data)
    {
        $this->first_name = trim($data['first_name']);
        $this->last_name = trim($data['last_name']);
        $this->email = trim($data['email']);
        $this->employee_code = trim($data['employee_code']);
        $this->password = $data['password'];
        $this->role_id = (int)($data['role_id'] ?? 3); // default: employee

        $this->validate();
    }

    private function validate()
    {
        v::stringType()->length(2, 50)->check($this->first_name);
        v::stringType()->length(2, 50)->check($this->last_name);
        v::email()->check($this->email);
        v::digit()->length(7, 7)->check($this->employee_code);
        v::stringType()->length(6, null)->check($this->password);
    }
}
