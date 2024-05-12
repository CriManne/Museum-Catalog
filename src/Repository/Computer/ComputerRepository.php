<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use AbstractRepo\Repository\AbstractRepository;

use App\Model\Computer\Computer;

use App\Model\Computer\Cpu;
use App\Model\Computer\Os;
use App\Model\Computer\Ram;
use App\Model\GenericObject;
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
