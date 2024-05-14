<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use AbstractRepo\Repository\AbstractRepository;

use App\Models\Computer\Computer;

use App\Models\Computer\Cpu;
use App\Models\Computer\Os;
use App\Models\Computer\Ram;
use App\Models\GenericObject;
use App\Util\ORM;
use ReflectionException;

class ComputerRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public static function getModel(): string
    {
        return Computer::class;
    }
}
