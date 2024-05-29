<?php

declare(strict_types=1);

namespace App\Repository\Computer;

use App\Models\Computer\Computer;
use App\Repository\BaseRepository;

/**
 * @method Computer|null findById($id)
 */
class ComputerRepository extends BaseRepository
{
    /**
     * @return string
     */
    public static function getModel(): string
    {
        return Computer::class;
    }
}
