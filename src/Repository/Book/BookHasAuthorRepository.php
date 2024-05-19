<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Models\Book\BookHasAuthor;
use App\Repository\BaseRepository;

class BookHasAuthorRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return BookHasAuthor::class;
    }
}
