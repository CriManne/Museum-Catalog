<?php

declare(strict_types=1);

namespace App\Model\Book;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces\IModel;

#[Entity('Author')]
class Author implements IModel
{
    public function __construct(
        #[Searchable]
        public ?string $firstname,
        #[Searchable]
        public ?string $lastname,
        #[PrimaryKey(autoIncrement: true)]
        public ?int    $id = null
    )
    {
    }
}
