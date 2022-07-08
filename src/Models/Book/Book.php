<?php

    declare(strict_types=1);

    namespace Models\Book;

    use Models\GenericObject;

    class Book{

        public GenericObject $genericObject;
        public string $Title;
        public string $publisher;
        public int $Year;
        public string $ISBN;
        public int $PageCount;
        public Author $authors;

        public function __construct(
            GenericObject $genericObject,
            string $Title,
            string $publisher,
            int $Year,
            string $ISBN,
            int $PageCount,
            Author $authors
        ){
            $this->genericObject = $genericObject;
            $this->Title = $Title;
            $this->publisher = $publisher;
            $this->Year = $Year;
            $this->ISBN = $ISBN;
            $this->PageCount = $PageCount;
            $this->authors = $authors;
        }

    }
?>