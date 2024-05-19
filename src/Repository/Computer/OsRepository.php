<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use App\Models\Computer\Os;
use App\Repository\BaseRepository;

/**
 * @method Os|null findById($id)
 */
class OsRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return Os::class;
    }
}
