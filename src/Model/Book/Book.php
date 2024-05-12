<?php

declare(strict_types=1);

namespace App\Model\Book;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\OneToMany;
use AbstractRepo\Attributes\OneToOne;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;
use App\Model\GenericObject;

#[Entity('Book')]
class Book implements IModel
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        #[OneToOne(columnName: 'objectId')]
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
