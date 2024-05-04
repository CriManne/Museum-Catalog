<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use AbstractRepo\Repository\AbstractRepository;
use App\Model\Computer\Os;

class OsRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Os::class;
    }
}
