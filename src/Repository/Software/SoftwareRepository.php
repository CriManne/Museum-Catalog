<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\Repository\AbstractRepository;
use App\Models\Computer\Os;
use App\Models\GenericObject;
use App\Models\Software\Software;
use App\Models\Software\SoftwareType;
use App\Models\Software\SupportType;
use App\Util\ORM;
use ReflectionException;

class SoftwareRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Software::class;
    }
}
