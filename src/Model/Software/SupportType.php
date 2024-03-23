<?php

declare(strict_types=1);

namespace App\Model\Software;

class SupportType
{
    public function __construct(
        public string $name,
        public ?int   $id = null
    )
    {
    }
}
