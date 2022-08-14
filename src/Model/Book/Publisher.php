<?php

    declare(strict_types=1);

    namespace App\Model\Book;

    class Publisher{

        public ?int $PublisherID;
        public string $Name;
        public ?string $Erased;

        public function __construct(
            ?int $PublisherID,
            string $Name,
            ?string $Erased = null
        )
        {
            $this->PublisherID = $PublisherID;
            $this->Name = $Name;
            $this->Erased = $Erased;
        }
    }
?>