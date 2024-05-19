<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Repository\AbstractRepository;
use App\Models\Software\Software;

/**
 * @method Software|null findById($id)
 * @method Software|null findFirst(?FetchParams $params = null)
 */
class SoftwareRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Software::class;
    }
}
