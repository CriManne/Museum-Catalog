<?php

declare(strict_types=1);

namespace App\Model\Computer;

class Os
{
    public function __construct(
        public string $name,
        public ?int   $id = null
    )
    {
    }
}
