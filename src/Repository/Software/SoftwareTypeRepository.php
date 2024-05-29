<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\DataModels\FetchParams;
use App\Models\Software\SoftwareType;
use App\Repository\BaseRepository;

/**
 * @method SoftwareType|null findFirst(?FetchParams $params)
 * @method SoftwareType|null findById(int $id)
 */
class SoftwareTypeRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return SoftwareType::class;
    }
}
