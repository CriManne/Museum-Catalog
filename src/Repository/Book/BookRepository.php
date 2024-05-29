<?php

declare(strict_types=1);

namespace App\Repository\Book;

use AbstractRepo\DataModels\FetchParams;
use App\Models\Book\Book;
use App\Repository\BaseRepository;

/**
 * @method Book|null findFirst(?FetchParams $params = null)
 * @method Book|null findById($id)
 */
class BookRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return Book::class;
    }
}
