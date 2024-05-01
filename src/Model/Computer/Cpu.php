<?php

declare(strict_types=1);

namespace App\Model\Computer;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;

#[Entity('Cpu')]
class Cpu implements IModel
{
    public function __construct(
        #[Searchable]
        public string $modelName,
        #[Searchable]
        public string $speed,
        #[PrimaryKey(autoIncrement: true)]
        public ?int   $id = null
    )
    {
    }
}
