<?php

declare(strict_types=1);

namespace App\Model\Peripheral;


class PeripheralType {

    public ?int $PeripheralTypeID;
    public string $Name;

    public function __construct(
        string $Name,
        ?int $PeripheralTypeID = null
    ) {
        $this->PeripheralTypeID = $PeripheralTypeID;
        $this->Name = $Name;
    }
}
