<?php

declare(strict_types=1);

namespace App\Repository\Book;

use AbstractRepo\DataModels\FetchParams;
use App\Models\Book\BookHasAuthor;
use App\Repository\BaseRepository;

/**
 * @method BookHasAuthor[] find(?FetchParams $params)
 * @method BookHasAuthor|null findById($id)
 */
class BookHasAuthorRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return BookHasAuthor::class;
    }
}
