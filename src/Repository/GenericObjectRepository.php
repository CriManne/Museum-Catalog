<?php

declare(strict_types=1);

namespace App\Repository;

use AbstractRepo\Repository\AbstractRepository;
use App\Model\GenericObject;

class GenericObjectRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return GenericObject::class;
    }
}
