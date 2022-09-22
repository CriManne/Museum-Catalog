<?php

declare(strict_types=1);

namespace App\Model\Computer;

class Ram {
    public ?int $RamID;
    public string $ModelName;
    public string $Size;
    public ?string $Erased;

    public function __construct(
        string $ModelName,
        string $Size,
        ?int $RamID=null,
        ?string $Erased = null
    ) {
        $this->RamID = $RamID;
        $this->ModelName = $ModelName;
        $this->Size = $Size;
        $this->Erased = $Erased;
    }
}
