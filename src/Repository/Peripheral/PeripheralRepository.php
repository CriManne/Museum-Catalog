<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Repository\AbstractRepository;
use App\Models\Peripheral\Peripheral;

/**
 * @method Peripheral|null findById($id)
 * @method Peripheral|null findFirst(?FetchParams $params = null)
 */
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
