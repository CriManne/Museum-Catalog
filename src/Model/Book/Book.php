<?php

    declare(strict_types=1);

    namespace App\Model\Book;

    use App\Model\GenericObject;

    class Book extends GenericObject{

        public string $Title;
        public string $publisher;
        public int $Year;
        public string $ISBN;
        public int $PageCount;
        public array $authors;

        public function __construct(
            string $ObjectID,
            string $Note = null,
            string $Url = null,
            string $Tag = null,
            string $Active,
            string $Erased = null,
            string $Title,
            string $publisher,
            int $Year,
            string $ISBN,
            int $PageCount,
            array $authors
        ){
            parent::__construct($ObjectID,$Note,$Url,$Tag,$Active,$Erased);
            $this->Title = $Title;
            $this->publisher = $publisher;
            $this->Year = $Year;
            $this->ISBN = $ISBN;
            $this->PageCount = $PageCount;
            $this->authors = $authors;
        }

    }
?>