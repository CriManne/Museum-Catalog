<?php

declare(strict_types=1);

namespace App\Model\Peripheral;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\OneToOne;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;
use App\Model\GenericObject;

#[Entity('Peripheral')]
class Peripheral implements IModel
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
