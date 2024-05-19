<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Repository\AbstractRepository;
use App\Models\Software\SupportType;

/**
 * @method SupportType|null findById($id)
 * @method SupportType|null findFirst(?FetchParams $params = null)
 */
class SupportTypeRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return SupportType::class;
    }
}
