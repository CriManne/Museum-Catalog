<?php

declare(strict_types=1);

namespace App\Model\Peripheral;

use App\Model\GenericObject;

class Peripheral extends GenericObject {

    public string $ModelName;
    public PeripheralType $PeripheralType;

    public function __construct(
        string $ObjectID,
        string $Note = null,
        string $Url = null,
        string $Tag = null,
        string $Active,
        string $Erased = null,
        string $ModelName,
        PeripheralType $PeripheralType
    ) {
        parent::__construct($ObjectID, $Note, $Url, $Tag, $Active, $Erased);
        $this->ModelName = $ModelName;
        $this->PeripheralType = $PeripheralType;
    }
}
