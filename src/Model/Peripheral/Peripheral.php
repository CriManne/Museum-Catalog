<?php

declare(strict_types=1);

namespace App\Model\Peripheral;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ForeignKey;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Enums\Relationship;
use AbstractRepo\Interfaces\IModel;
use App\Model\GenericObject;

#[Entity('Peripheral')]
class Peripheral implements IModel
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[ForeignKey(relationship: Relationship::ONE_TO_ONE, columnName: 'objectId')]
        public GenericObject    $genericObject,
        #[Searchable]
        public string         $modelName,
        #[ForeignKey(relationship: Relationship::MANY_TO_ONE, columnName: 'peripheralTypeId')]
        public PeripheralType $peripheralType,
    )
    {
    }
}
