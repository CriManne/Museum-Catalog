<?php

declare(strict_types=1);

namespace App\Model\Book;

class Author {

    public ?int $AuthorID;
    public ?string $firstname;
    public ?string $lastname;
    public ?string $Erased;

    public function __construct(
        ?string $firstname,
        ?string $lastname,
        ?int $AuthorID = null,
        ?string $Erased = null
    ) {
        $this->AuthorID = $AuthorID;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->Erased = $Erased;
    }
}
