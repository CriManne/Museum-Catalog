<?php

declare(strict_types=1);

namespace App\Repository\Book;

use AbstractRepo\Repository\AbstractRepository;
use App\Models\Book\Publisher;

/**
 * @method Publisher|null findById($id)
 */
class PublisherRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Publisher::class;
    }
}
