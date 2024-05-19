<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use AbstractRepo\Repository\AbstractRepository;

use App\Models\Computer\Computer;

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
