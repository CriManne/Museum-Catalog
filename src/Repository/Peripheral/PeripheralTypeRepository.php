<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use AbstractRepo\DataModels\FetchParams;
use App\Models\Peripheral\PeripheralType;
use App\Repository\BaseRepository;

/**
 * @method PeripheralType|null findById($id)
 * @method PeripheralType|null findFirst(?FetchParams $params = null)
 */
class PeripheralTypeRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return PeripheralType::class;
    }
}
