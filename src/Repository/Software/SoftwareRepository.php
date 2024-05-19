<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\Repository\AbstractRepository;
use App\Models\Software\Software;

class SoftwareRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Software::class;
    }
}
