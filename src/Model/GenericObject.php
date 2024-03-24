<?php

declare(strict_types=1);

namespace App\Model;

class GenericObject
{
    public function __construct(
        public string $objectId,
        public ?string $note,
        public ?string $url,
        public ?string $tag
    )
    {
    }
}
