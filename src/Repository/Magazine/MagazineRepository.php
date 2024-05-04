<?php

declare(strict_types=1);

namespace App\Repository\Magazine;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Interfaces\IModel;
use AbstractRepo\Interfaces\IRepository;
use AbstractRepo\Repository\AbstractRepository;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use App\Model\Book\Publisher;
use App\Model\GenericObject;
use App\Model\Magazine\Magazine;

use App\Repository\Book\PublisherRepository;

use App\Util\ORM;
use PDO;
use PDOException;

class MagazineRepository extends AbstractRepository
{
    public PublisherRepository $publisherRepository;

    public function __construct(
        PDO                 $pdo,
        PublisherRepository $publisherRepository
    )
    {
        parent::__construct($pdo);
        $this->publisherRepository = $publisherRepository;
    }

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

    /**
     * Return a new instance of Magazine from an array
     * @param array $rawMagazine The raw magazine object
     * @return Magazine The new instance of magazine with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawMagazine): Magazine
    {
        return new Magazine(
            ORM::getNewInstance(GenericObject::class, $rawMagazine['genericObject']),
            $rawMagazine["title"],
            intval($rawMagazine["year"]),
            intval($rawMagazine["magazineNumber"]),
            ORM::getNewInstance(Publisher::class, $rawMagazine['publisher'])
        );
    }
}
