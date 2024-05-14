<?php

declare(strict_types=1);

namespace App\Models\Magazine;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\OneToOne;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;
use App\Models\Book\Publisher;
use App\Models\GenericObject;
use App\Models\IArtifact;

#[Entity('Magazine')]
class Magazine implements IArtifact
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[OneToOne(columnName: 'objectId')]
        public GenericObject    $genericObject,
        #[Searchable]
        public string           $title,
        #[Searchable]
        public int              $year,
        #[Searchable]
        public int              $magazineNumber,
        #[ManyToOne(columnName: 'publisherId')]
        public Publisher $publisher,
    )
    {
    }
}
