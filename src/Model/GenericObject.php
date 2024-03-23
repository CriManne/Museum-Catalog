<?php

declare(strict_types=1);

namespace App\Model;

class GenericObject
{
    public function __construct(
        public string $objectID,
        public ?string $note,
        public ?string $url,
        public ?string $tag
    )
    {
    }
}
