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

    /**
     * Return a new instance of Peripheral from an array
     * @param array $rawPeripheral The raw peripheral object
     * @return Peripheral The new instance of peripheral with the fk filled with the result of selects
     * @throws ReflectionException
     */
    function returnMappedObject(array $rawPeripheral): Peripheral
    {
        return new Peripheral(
            ORM::getNewInstance(GenericObject::class, $rawPeripheral["genericObject"]),
            $rawPeripheral["modelName"],
            ORM::getNewInstance(PeripheralType::class, $rawPeripheral["peripheralType"]),
        );
    }
}
