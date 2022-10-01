<?php

declare(strict_types=1);

namespace App\Model\Book;

class Author {

    public ?int $AuthorID;
    public ?string $firstname;
    public ?string $lastname;

    public function __construct(
        ?string $firstname,
        ?string $lastname,
        ?int $AuthorID = null
    ) {
        $this->AuthorID = $AuthorID;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }
}
