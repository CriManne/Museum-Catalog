<?php

    declare(strict_types=1);

    namespace App\Model\Book;

    class BookAuthor{

        public ?string $BookID;
        public ?int $AuthorID;

        public function __construct(
            ?string $BookID,
            ?int $AuthorID
        )
        {
            $this->BookID = $BookID;
            $this->AuthorID = $AuthorID;
        }
    }
?>