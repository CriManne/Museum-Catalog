<?php

declare(strict_types=1);

namespace App\Models\Peripheral;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\OneToOne;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use App\Models\GenericObject;
use App\Models\IArtifact;

#[Entity('Peripheral')]
class Peripheral implements IArtifact
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[OneToOne(columnName: 'objectId')]
        public GenericObject    $genericObject,
        #[Searchable]
        public string         $modelName,
        #[ManyToOne(columnName: 'peripheralTypeId')]
        #[Searchable]
        public PeripheralType $peripheralType,
    )
    {
    }
}
