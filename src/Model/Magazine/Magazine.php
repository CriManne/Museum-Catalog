<?php

declare(strict_types=1);

namespace App\Model\Magazine;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\OneToOne;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;
use App\Model\Book\Publisher;
use App\Model\GenericObject;

#[Entity('Magazine')]
class Magazine implements IModel
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
