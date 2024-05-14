<?php

declare(strict_types=1);

namespace App\Repository\Book;

use AbstractRepo\Interfaces\IRepository;
use AbstractRepo\Repository\AbstractRepository;
use App\Models\Book\Publisher;

class PublisherRepository extends AbstractRepository implements IRepository
{
    public static function getModel(): string
    {
        return Publisher::class;
    }
}
