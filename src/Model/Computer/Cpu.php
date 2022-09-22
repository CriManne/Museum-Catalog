<?php

declare(strict_types=1);

namespace App\Model\Computer;

class Cpu {

    public ?int $CpuID;
    public string $ModelName;
    public string $Speed;
    public ?string $Erased;

    public function __construct(
        string $ModelName,
        string $Speed,
        ?int $CpuID=null,
        ?string $Erased = null
    ) {
        $this->CpuID = $CpuID;
        $this->ModelName = $ModelName;
        $this->Speed = $Speed;
        $this->Erased = $Erased;
    }
}
