<?php

declare(strict_types=1);

namespace App\DataModels\User;

class UserResponse
{
    public function __construct(
        public string $Email,
        public string $Firstname,
        public string $Lastname,
        public int    $Privilege = 0
    )
    {
    }
}
