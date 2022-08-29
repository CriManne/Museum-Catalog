<?php

declare(strict_types=1);

namespace App\Model\Peripheral;


class PeripheralType {

    public ?int $PeripheralTypeID;
    public string $Name;
    public ?string $Erased;

    public function __construct(
        ?int $PeripheralTypeID,
        string $Name,
        ?string $Erased = null
    ) {
        $this->PeripheralTypeID = $PeripheralTypeID;
        $this->Name = $Name;
        $this->Erased = $Erased;
    }
}
