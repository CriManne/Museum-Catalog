<?php

declare(strict_types=1);

namespace App\Repository\Magazine;

use App\Models\Magazine\Magazine;
use App\Repository\BaseRepository;

/**
 * @method Magazine|null findById($id)
 */
class MagazineRepository extends BaseRepository
{
    public static function getModel(): string
    {
        return Magazine::class;
    }
}
