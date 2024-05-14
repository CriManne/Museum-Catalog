<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use AbstractRepo\Repository\AbstractRepository;
use App\Models\Peripheral\PeripheralType;

class PeripheralTypeRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return PeripheralType::class;
    }
}
