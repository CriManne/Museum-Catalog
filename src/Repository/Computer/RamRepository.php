<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use App\Models\Computer\Ram;
use App\Repository\BaseRepository;

/**
 * @method Ram|null findById($id)
 */
class RamRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return Ram::class;
    }
}
