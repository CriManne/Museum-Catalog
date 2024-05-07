<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\Repository\AbstractRepository;
use App\Model\Software\SupportType;

class SupportTypeRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return SupportType::class;
    }
}
