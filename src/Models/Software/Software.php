<?php

declare(strict_types=1);

namespace App\Models\Software;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\OneToOne;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use App\Models\Computer\Os;
use App\Models\GenericObject;
use App\Models\IArtifact;

#[Entity('Software')]
class Software implements IArtifact
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[OneToOne(columnName: 'objectId')]
        #[Searchable]
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
