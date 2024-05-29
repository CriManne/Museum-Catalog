<?php

declare(strict_types=1);

namespace App\DataModels\Response;

class GenericArtifactResponse
{
    public function __construct(
        public string $objectId,
        public string $title,
        public array  $descriptors,
        public string $category,
        public ?string $note = null,
        public ?string $url = null,
        public ?string $tag = null
    )
    {
    }
}
