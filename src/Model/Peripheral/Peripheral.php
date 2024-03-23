<?php

declare(strict_types=1);

namespace App\Model\Peripheral;

use App\Model\GenericObject;

class Peripheral extends GenericObject
{
    public function __construct(
        public string         $objectId,
        public string         $modelName,
        public PeripheralType $peripheralType,
        string                $note = null,
        string                $url = null,
        string                $tag = null,
    )
    {
        parent::__construct($objectId, $note, $url, $tag);
    }
}
