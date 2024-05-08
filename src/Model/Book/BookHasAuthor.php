<?php

declare(strict_types=1);

namespace App\Model\Book;

class BookHasAuthor
{
    public function __construct(
        public ?string $bookId,
        public ?int    $authorId
    )
    {
    }
}
