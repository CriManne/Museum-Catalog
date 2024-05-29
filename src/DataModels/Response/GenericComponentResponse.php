<?php

declare(strict_types=1);

namespace App\DataModels\Response;

class GenericComponentResponse
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $category
    )
    {
    }
}
