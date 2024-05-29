<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Models\Book\Publisher;
use App\Repository\BaseRepository;

/**
 * @method Publisher|null findById($id)
 */
class PublisherRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return Publisher::class;
    }
}
