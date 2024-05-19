<?php

declare(strict_types=1);

namespace App\Repository\Book;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Repository\AbstractRepository;
use App\Models\Book\Book;

/**
 * @method Book|null findFirst(?FetchParams $params = null)
 * @method Book|null findById($id)
 */
class BookRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Book::class;
    }
}
