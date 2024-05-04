<?php

declare(strict_types=1);

namespace App\Model\Computer;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ForeignKey;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Enums\Relationship;
use AbstractRepo\Interfaces\IModel;
use App\Model\GenericObject;

#[Entity('Computer')]
class Computer implements IModel
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[ForeignKey(relationship: Relationship::ONE_TO_ONE, columnName: 'objectId')]
        public GenericObject    $genericObject,
        #[Searchable]
        public string  $modelName,
        #[Searchable]
        public int     $year,
        #[Searchable]
        public ?string $hddSize,
        #[ForeignKey(relationship: Relationship::MANY_TO_ONE, columnName: 'cpuId')]
        public Cpu     $cpu,
        #[ForeignKey(relationship: Relationship::MANY_TO_ONE, columnName: 'ramId')]
        public Ram     $ram,
        #[ForeignKey(relationship: Relationship::MANY_TO_ONE, columnName: 'osId')]
        public ?Os     $os
    )
    {
    }
}
