<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\DataModels\FetchParams;
use App\Models\Software\SupportType;
use App\Repository\BaseRepository;

/**
 * @method SupportType|null findById($id)
 * @method SupportType|null findFirst(?FetchParams $params = null)
 */
class SupportTypeRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return SupportType::class;
    }
}
