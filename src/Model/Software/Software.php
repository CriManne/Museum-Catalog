<?php

declare(strict_types=1);

namespace App\Model\Software;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ForeignKey;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Enums\Relationship;
use AbstractRepo\Interfaces\IModel;
use App\Model\Computer\Os;
use App\Model\GenericObject;

#[Entity('Software')]
class Software implements IModel
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[ForeignKey(relationship: Relationship::ONE_TO_ONE, columnName: 'objectId')]
        public GenericObject    $genericObject,
        #[Searchable]
        public string       $title,
        #[Searchable]
        #[ForeignKey(relationship: Relationship::MANY_TO_ONE, columnName: 'osId')]
        public Os           $os,
        #[Searchable]
        #[ForeignKey(relationship: Relationship::MANY_TO_ONE, columnName: 'softwareTypeId')]
        public SoftwareType $softwareType,
        #[Searchable]
        #[ForeignKey(relationship: Relationship::MANY_TO_ONE, columnName: 'supportTypeId')]
        public SupportType  $supportType
    )
    {
    }
}
