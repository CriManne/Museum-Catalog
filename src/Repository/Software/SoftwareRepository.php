<?php

declare(strict_types=1);

namespace App\Repository\Software;

use AbstractRepo\Repository\AbstractRepository;
use App\Model\Computer\Os;
use App\Model\GenericObject;
use App\Model\Software\Software;
use App\Model\Software\SoftwareType;
use App\Model\Software\SupportType;
use App\Util\ORM;
use ReflectionException;

class SoftwareRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Software::class;
    }
}
