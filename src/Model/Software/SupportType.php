<?php

declare(strict_types=1);

namespace App\Model\Software;

class SupportType {

    public ?int $SupportTypeID;
    public string $Name;

    public function __construct(
        string $Name,
        ?int $SupportTypeID = null
    ) {
        $this->SupportTypeID = $SupportTypeID;
        $this->Name = $Name;
    }
}
