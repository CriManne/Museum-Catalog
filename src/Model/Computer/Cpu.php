<?php

declare(strict_types=1);

namespace App\Model\Computer;

class Cpu
{
    public function __construct(
        public string $modelName,
        public string $speed,
        public ?int   $id = null
    )
    {
    }
}
