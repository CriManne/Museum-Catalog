<?php

declare(strict_types=1);

namespace App\Model\Software;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;

#[Entity('SoftwareType')]
class SoftwareType implements IModel
{
    public function __construct(
        #[Searchable]
        public string $name,
        #[PrimaryKey(autoIncrement: true)]
        public ?int   $id = null
    )
    {
    }
}
