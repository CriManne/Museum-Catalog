<?php

declare(strict_types=1);

namespace App\Model\Computer;

class Ram
{
    public function __construct(
        public string $modelName,
        public string $size,
        public ?int   $id = null
    )
    {
    }
}
