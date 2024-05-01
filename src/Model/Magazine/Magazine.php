<?php

declare(strict_types=1);

namespace App\Model\Magazine;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ForeignKey;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Enums\Relationship;
use AbstractRepo\Interfaces\IModel;
use App\Model\Book\Publisher;
use App\Model\GenericObject;

#[Entity('Magazine')]
class Magazine implements IModel
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[ForeignKey(relationship: Relationship::ONE_TO_ONE, columnName: 'objectId')]
        public GenericObject    $genericObject,
        #[Searchable]
        public string           $title,
        #[Searchable]
        public int              $year,
        #[Searchable]
        public int              $magazineNumber,
        #[ForeignKey(relationship: Relationship::MANY_TO_ONE, columnName: 'publisherId')]
        public Publisher $publisher,
    )
    {
    }
}
