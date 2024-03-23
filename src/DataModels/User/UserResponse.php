<?php

declare(strict_types=1);

namespace App\DataModels\User;

class UserResponse
{
    public function __construct(
        public string $email,
        public string $firstname,
        public string $lastname,
        public int    $privilege = 0
    )
    {
    }
}
