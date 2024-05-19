<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use AbstractRepo\Repository\AbstractRepository;
use App\Models\Peripheral\Peripheral;

class PeripheralRepository extends AbstractRepository
{
    /**
     * @return string
     */
    static public function getModel(): string
    {
        return Peripheral::class;
    }
}
