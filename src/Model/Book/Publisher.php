<?php

declare(strict_types=1);

namespace App\Model\Book;

class Publisher
{
    public function __construct(
        public ?string $name,
        public ?int    $id = null
    )
    {
    }
}
