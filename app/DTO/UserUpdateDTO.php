<?php

namespace Elagiou\VacationPortal\DTO;

use Respect\Validation\Validator as v;

class UserUpdateDTO
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public ?string $password;

    public function __construct(array $data)
    {
        $this->id = (int)$data['id'];
        $this->first_name = trim($data['first_name']);
        $this->last_name = trim($data['last_name']);
        $this->email = trim($data['email']);
        $this->password = $data['password'] ?? null;

        $this->validate();
    }

    private function validate()
    {
        v::intType()->positive()->check($this->id);
        v::stringType()->length(2, 50)->check($this->first_name);
        v::stringType()->length(2, 50)->check($this->last_name);
        v::email()->check($this->email);
        if ($this->password) {
            v::stringType()->length(6, null)->check($this->password);
        }
    }
}
