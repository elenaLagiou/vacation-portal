<?php

namespace Elagiou\VacationPortal\Models;

class User
{
    public int $id;
    public string $username;
    public string $email;
    public string $password; // hashed
    public int $role_id;
    public string $first_name;
    public string $last_name;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
