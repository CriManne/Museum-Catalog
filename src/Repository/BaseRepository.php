<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

class BaseRepository
{
    public PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
