<?php

declare(strict_types=1);

namespace App\Repository\Magazine;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Interfaces\IModel;
use AbstractRepo\Repository\AbstractRepository;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use App\Models\Book\Publisher;
use App\Models\GenericObject;
use App\Models\Magazine\Magazine;

use App\Util\ORM;
use ReflectionException;

class MagazineRepository extends AbstractRepository
{
    public static function getModel(): string
    {
        return Magazine::class;
    }

    /**
     * Select magazine by title
     * @param string $title The magazine title to select
     * @return Magazine|IModel|null The magazine selected, null if not found
     * @throws AbstractRepositoryException
     */
    public function findByTitle(string $title): Magazine|IModel|null
    {
        return $this->findFirst(new FetchParams(
            conditions: "title LIKE :title",
            bind: [
                "title" => $title
            ]
        ));
    }
}
