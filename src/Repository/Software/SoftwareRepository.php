<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\DataModels\FetchParams;
use App\Models\Software\Software;
use App\Repository\BaseRepository;

/**
 * @method Software|null findById($id)
 * @method Software|null findFirst(?FetchParams $params = null)
 */
class SoftwareRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return Software::class;
    }
}
