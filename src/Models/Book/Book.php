<?php

declare(strict_types=1);

namespace App\Models\Book;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\OneToMany;
use AbstractRepo\Attributes\OneToOne;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use App\Models\GenericObject;
use App\Models\IArtifact;

#[Entity('Book')]
class Book implements IArtifact
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[OneToOne(columnName: 'objectId')]
        #[Searchable]
        public GenericObject    $genericObject,
        #[Searchable]
        public string    $title,
        #[ManyToOne(columnName: 'publisherId')]
        #[Searchable]
        public Publisher $publisher,
        #[Searchable]
        public int       $year,
        #[OneToMany(
            referencedColumn: 'bookId',
            referencedClass: BookHasAuthor::class
        )]
        public ?array     $authors = null,
        #[Searchable]
        public ?string   $isbn = null,
        #[Searchable]
        public ?int      $pages = null,
    )
    {
    }
}
