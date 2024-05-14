<?php

declare(strict_types=1);

namespace App\Repository\Book;

use AbstractRepo\Repository\AbstractRepository;
use App\Models\Book\Book;

class BookRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Book::class;
    }
}
