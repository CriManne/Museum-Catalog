<?php

declare(strict_types=1);

namespace App\Model\Software;

class SoftwareType {

    public ?int $SoftwareTypeID;
    public string $Name;
    public ?string $Erased;

    public function __construct(
        ?int $SoftwareTypeID,
        string $Name,
        string $Erased = null
    ) {
        $this->SoftwareTypeID = $SoftwareTypeID;
        $this->Name = $Name;
        $this->Erased = $Erased;
    }
}
