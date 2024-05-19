<?php

declare(strict_types=1);

namespace App\Repository\Magazine;

use AbstractRepo\Repository\AbstractRepository;
use App\Models\Magazine\Magazine;

/**
 * @method Magazine|null findById($id)
 */
class MagazineRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Magazine::class;
    }
}
