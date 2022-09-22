<?php

declare(strict_types=1);

namespace App\Model\Software;

class SoftwareType {

    public ?int $SoftwareTypeID;
    public string $Name;
    public ?string $Erased;

    public function __construct(
        string $Name,
        ?int $SoftwareTypeID=null,
        string $Erased = null
    ) {
        $this->SoftwareTypeID = $SoftwareTypeID;
        $this->Name = $Name;
        $this->Erased = $Erased;
    }
}
