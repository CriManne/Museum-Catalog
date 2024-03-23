<?php

declare(strict_types=1);

namespace App\Model;

use AbstractRepo\Attributes;
use AbstractRepo\Interfaces;

#[Attributes\Entity('User')]
class User implements Interfaces\IModel
{
    public function __construct(
        #[Attributes\Key]
        #[Attributes\Searchable]
        public string $email,
        public string $password,
        #[Attributes\Searchable]
        public string $firstname,
        #[Attributes\Searchable]
        public string $lastname,
        public int    $privilege = 0
    )
    {
    }
}
