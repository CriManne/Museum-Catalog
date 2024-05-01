<?php

declare(strict_types=1);

namespace App\Model;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;

#[Entity('GenericObject')]
class GenericObject implements IModel
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        public string  $id,
        #[Searchable]
        public ?string $note = null,
        #[Searchable]
        public ?string $url = null,
        #[Searchable]
        public ?string $tag = null
    )
    {
    }
}
