<?php

declare(strict_types=1);

namespace App\Model\Computer;

class Os {
    public ?int $OsID;
    public string $Name;
    public ?string $Erased;

    public function __construct(
        string $Name,
        ?int $OsID=null,
        ?string $Erased = null
    ) {
        $this->OsID = $OsID;
        $this->Name = $Name;
        $this->Erased = $Erased;
    }
}
