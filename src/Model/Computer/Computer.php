<?php

declare(strict_types=1);

namespace App\Model\Computer;

use App\Model\GenericObject;

class Computer extends GenericObject {

    public string $ModelName;
    public int $Year;
    public ?string $HddSize;
    public Cpu $Cpu;
    public Ram $Ram;
    public ?Os $Os;

    public function __construct(
        string $ObjectID,
        string $Note = null,
        string $Url = null,
        string $Tag = null,
        string $ModelName,
        int $Year,
        ?string $HddSize,
        Cpu $Cpu,
        Ram $Ram,
        ?Os $Os
    ) {
        parent::__construct($ObjectID, $Note, $Url, $Tag);
        $this->ModelName = $ModelName;
        $this->Year = $Year;
        $this->Cpu = $Cpu;
        $this->Ram = $Ram;
        $this->HddSize = $HddSize;
        $this->Os = $Os;
    }
}
