<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use AbstractRepo\Interfaces\IRepository;
use AbstractRepo\Repository\AbstractRepository;
use App\Model\Computer\Ram;

class RamRepository extends AbstractRepository implements IRepository
{
    public static function getModel(): string
    {
        return Ram::class;
    }
}
