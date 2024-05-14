<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Repository\AbstractRepository;
use App\Models\Software\SoftwareType;

/**
 * @method SoftwareType|null findFirst(?FetchParams $params)
 * @method SoftwareType|null findById(int $id)
 */
class SoftwareTypeRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return SoftwareType::class;
    }
}
