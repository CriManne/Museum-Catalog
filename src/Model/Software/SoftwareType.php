<?php

declare(strict_types=1);

namespace App\Model\Software;

class SoftwareType {

    public ?int $SoftwareTypeID;
    public string $Name;

    public function __construct(
        string $Name,
        ?int $SoftwareTypeID=null
    ) {
        $this->SoftwareTypeID = $SoftwareTypeID;
        $this->Name = $Name;
    }
}
