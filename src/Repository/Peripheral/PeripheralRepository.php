<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use AbstractRepo\Repository\AbstractRepository;
use App\Model\GenericObject;
use App\Model\Peripheral\Peripheral;
use App\Model\Peripheral\PeripheralType;
use App\Util\ORM;
use ReflectionException;

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
