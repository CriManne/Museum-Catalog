<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Repository\AbstractRepository;
use App\Models\Peripheral\PeripheralType;

/**
 * @method PeripheralType|null findById($id)
 * @method PeripheralType|null findFirst(?FetchParams $params = null)
 */
class PeripheralTypeRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return PeripheralType::class;
    }
}
