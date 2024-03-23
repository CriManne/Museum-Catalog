<?php

declare(strict_types=1);

namespace App\Model\Peripheral;

class PeripheralType
{
    public function __construct(
        public string $name,
        public ?int   $id = null
    )
    {
    }
}
