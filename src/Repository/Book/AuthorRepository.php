<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Models\Book\Author;
use App\Repository\BaseRepository;

/**
 * @method Author|null findById($id)
 */
class AuthorRepository extends BaseRepository
{
    /**
     * @return string
     */
    public static function getModel(): string
    {
        return Author::class;
    }
}
