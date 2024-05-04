<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use AbstractRepo\Repository\AbstractRepository;
use App\Model\Computer\Cpu;

class CpuRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Cpu::class;
    }
}
