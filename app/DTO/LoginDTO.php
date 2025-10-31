<?php

namespace Elagiou\VacationPortal\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class LoginDTO extends DataTransferObject
{
    public string $username;
    public string $password;
}
