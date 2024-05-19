<?php

declare(strict_types=1);

namespace App\Repository\Peripheral;

use AbstractRepo\DataModels\FetchParams;
use App\Models\Peripheral\Peripheral;
use App\Repository\BaseRepository;

/**
 * @method Peripheral|null findById($id)
 * @method Peripheral|null findFirst(?FetchParams $params = null)
 */
class PeripheralRepository extends BaseRepository
{
    /**
     * @return string
     */
    static public function getModel(): string
    {
        return Peripheral::class;
    }
}
