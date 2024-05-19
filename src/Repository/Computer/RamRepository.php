<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use AbstractRepo\Repository\AbstractRepository;
use App\Models\Computer\Ram;

/**
 * @method Ram|null findById($id)
 */
class RamRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Ram::class;
    }
}
