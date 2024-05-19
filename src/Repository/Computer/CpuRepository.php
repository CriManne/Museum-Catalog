<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use AbstractRepo\DataModels\FetchParams;
use App\Models\Computer\Cpu;
use App\Repository\BaseRepository;

/**
 * @method Cpu|null findById($id)
 * @method Cpu|null findFirst(?FetchParams $params = null)
 */
class CpuRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return Cpu::class;
    }
}
