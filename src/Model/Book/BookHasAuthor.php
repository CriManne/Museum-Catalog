<?php

declare(strict_types=1);

namespace App\Model\Book;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\ManyToOne;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Interfaces\IModel;

#[Entity('BookHasAuthor')]
class BookHasAuthor implements IModel
{
    public function __construct(
        #[ManyToOne(columnName: 'bookId')]
        public Book $book,
        #[ManyToOne(columnName: 'authorId')]
        public Author $author,
        #[PrimaryKey(autoIncrement: true)]
        public ?int $id = null
    )
    {
    }
}
