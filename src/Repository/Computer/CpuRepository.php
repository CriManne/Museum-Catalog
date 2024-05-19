<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Repository\AbstractRepository;
use App\Models\Computer\Cpu;

/**
 * @method Cpu|null findById($id)
 * @method Cpu|null findFirst(?FetchParams $params = null)
 */
class CpuRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Cpu::class;
    }
}
