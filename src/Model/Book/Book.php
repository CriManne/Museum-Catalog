<?php

declare(strict_types=1);

namespace App\Model\Book;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\OneToMany;
use AbstractRepo\Attributes\OneToOne;
use AbstractRepo\Attributes\PrimaryKey;
use App\Model\GenericObject;

#[Entity('Book')]
class Book extends GenericObject
{
    public function __construct(
        #[PrimaryKey(autoIncrement: false)]
        public string    $objectId,
        public string    $title,
        #[ManyToOne(columnName: 'publisherId')]
        public Publisher $publisher,
        public int       $year,
        #[OneToMany(
            referencedColumn: 'bookId',
            referencedClass: BookHasAuthor::class
        )]
        public ?array     $authors,
        string           $note = null,
        string           $url = null,
        string           $tag = null,
        public ?string   $isbn = null,
        public ?int      $pages = null,
    )
    {
        parent::__construct($objectId, $note, $url, $tag);
    }
}
