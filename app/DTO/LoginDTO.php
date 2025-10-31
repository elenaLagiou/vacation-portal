<?php

namespace Elagiou\VacationPortal\DTO;

class LoginDTO
{
    public string $username;
    public string $password;

    public function __construct(array $data)
    {
        $this->username = trim($data['username'] ?? '');
        $this->password = trim($data['password'] ?? '');
    }
}
