<?php

declare(strict_types=1);

namespace App\Model;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces;

#[Entity('User')]
class User implements Interfaces\IModel
{
    public function __construct(
        #[PrimaryKey]
        #[Searchable]
        public string $email,
        public string $password,
        #[Searchable]
        public string $firstname,
        #[Searchable]
        public string $lastname,
        public int    $privilege = 0
    )
    {
    }
}
