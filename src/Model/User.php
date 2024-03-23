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
        public string $Email,
        public string $Password,
        #[Attributes\Searchable]
        public string $Firstname,
        #[Attributes\Searchable]
        public string $Lastname,
        public int    $Privilege = 0
    )
    {
    }
}
