<?php

declare(strict_types=1);

namespace App\Repository\Book;

use AbstractRepo\Repository\AbstractRepository;
use App\Models\Book\Author;

class AuthorRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Author::class;
    }
}
