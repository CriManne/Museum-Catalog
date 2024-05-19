<?php

declare(strict_types=1);

namespace App\Repository;

use AbstractRepo\DataModels\FetchParams;
use App\Models\GenericObject;

/**
 * @method GenericObject|null findById($id)
 * @method GenericObject|null findFirst(?FetchParams $params = null)
 */
class GenericObjectRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return GenericObject::class;
    }
}
