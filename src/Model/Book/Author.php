<?php

declare(strict_types=1);

namespace App\Model\Book;

class Author
{
    public function __construct(
        public ?string $firstname,
        public ?string $lastname,
        public ?int    $id = null
    )
    {
    }
}
