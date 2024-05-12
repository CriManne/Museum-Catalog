<?php

declare(strict_types=1);

namespace App\Model\Software;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\OneToOne;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;
use App\Model\Computer\Os;
use App\Model\GenericObject;

#[Entity('Software')]
class Software implements IModel
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[OneToOne(columnName: 'objectId')]
        public GenericObject    $genericObject,
        #[Searchable]
        public string       $title,
        #[ManyToOne(columnName: 'osId')]
        #[Searchable]
        public Os           $os,
        #[ManyToOne(columnName: 'softwareTypeId')]
        #[Searchable]
        public SoftwareType $softwareType,
        #[ManyToOne(columnName: 'supportTypeId')]
        #[Searchable]
        public SupportType  $supportType
    )
    {
    }
}
