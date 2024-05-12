<?php

declare(strict_types=1);

namespace App\Model\Computer;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\OneToOne;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;
use App\Model\GenericObject;

#[Entity('Computer')]
class Computer implements IModel
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[OneToOne(columnName: 'objectId')]
        public GenericObject    $genericObject,
        #[Searchable]
        public string  $modelName,
        #[Searchable]
        public int     $year,
        #[Searchable]
        public ?string $hddSize,
        #[ManyToOne(columnName: 'cpuId')]
        public Cpu     $cpu,
        #[ManyToOne(columnName: 'ramId')]
        public Ram     $ram,
        #[ManyToOne(columnName: 'osId')]
        public ?Os     $os
    )
    {
    }
}
