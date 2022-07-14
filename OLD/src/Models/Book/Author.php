<?php

    declare(strict_types=1);

    namespace Models\Book;

    class Author{

        public int $AuthorID;
        public string $firstname;
        public string $lastname;

        public function __construct(
            int $AuthorID,
            string $firstname,
            string $lastname,
        )
        {
            $this->AuthorID = $AuthorID;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
        }

    }
?>