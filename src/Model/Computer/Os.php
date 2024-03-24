<?php

declare(strict_types=1);

namespace App\Model\Computer;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\Key;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;

#[Entity('Os')]
class Os implements IModel
{
    public function __construct(
        #[Searchable]
        public string $name,
        #[Key(identity: true)]
        public ?int   $id = null
    )
    {
    }
}
