<?php

declare(strict_types=1);

namespace App\Repository\Book;

use AbstractRepo\Repository\AbstractRepository;
use App\Models\Book\Author;

/**
 * @method Author|null findById($id)
 */
class AuthorRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public static function getModel(): string
    {
        return Author::class;
    }
}
