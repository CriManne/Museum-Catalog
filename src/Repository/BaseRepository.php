<?php

declare(strict_types=1);

namespace App\Repository;

use AbstractRepo\Exceptions\RepositoryException;
use AbstractRepo\Repository\AbstractRepository;
use App\Plugins\Injection\DIC;
use DI\DependencyException;
use DI\NotFoundException;

abstract class BaseRepository extends AbstractRepository
{
    /**
     * @throws RepositoryException
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct()
    {
        parent::__construct(DIC::getPdo());
    }
}
