<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\RepositoryException;
use PDO;
use App\Model\Software\Software;
use App\Model\Book\Book;
use App\Model\Magazine\Magazine;
use App\Model\Peripheral\Peripheral;
use App\Model\Computer\Computer;
use App\Model\Response\GenericObjectResponse;

use App\Repository\Book\BookRepository;
use App\Repository\Computer\ComputerRepository;
use App\Repository\Magazine\MagazineRepository;
use App\Repository\Peripheral\PeripheralRepository;
use App\Repository\Software\SoftwareRepository;
use PDOException;
use App\Util\ORM;

class GenericObjectRepository extends GenericRepository {

    public array $Repositories;

    public function __construct(
        PDO $pdo,
        SoftwareRepository $softwareRepository,
        ComputerRepository $computerRepository,
        BookRepository $bookRepository,
        PeripheralRepository $peripheralRepository,
        MagazineRepository $magazineRepository
    ) {
        parent::__construct($pdo);
        $this->Repositories = [
            'Software' => $softwareRepository,
            'Computer' => $computerRepository,
            'Book' => $bookRepository,
            'Peripheral' => $peripheralRepository,
            'Magazine' => $magazineRepository
        ];
    }

    /**
     * Select an object
     * @param string $ObjectID     The ObjectID to select
     * @return ?GenericObjectResponse            The Object selected, null if not found
     */
    public function selectById(string $ObjectID): ?GenericObjectResponse {

        $obj = null;
        $repoName = null;

        foreach ($this->Repositories as $RepoName => $Repo) {
            $result = $Repo->selectById($ObjectID);

            if ($result) {
                $obj = $result;
                $repoName = $RepoName;
            }
        }

        if ($obj) {
            return $this->$repoName($obj);
        }
        return null;
    }

    /**
     * Select all objects
     * @param ?string $category  The category to search in
     * @return array            The result array
     */
    public function selectAll(?string $category): array {

        $result = [];

        foreach ($this->Repositories as $RepoName => $Repo) {
            if ($category && $RepoName !== $category) {
                continue;
            }

            $unmappedResult = $Repo->selectAll();
            if (count($unmappedResult) > 0) {

                foreach ($unmappedResult as $item) {
                    $mappedObject = $Repo->returnMappedObject(json_decode(json_encode($item), true));

                    $result[] = $this->$RepoName($mappedObject);
                }
            }
        }

        //SORT BY OBJECT ID
        usort($result,function($a,$b){return strcmp($a->ObjectID,$b->ObjectID);});
        return $result;
    }

    /**
     * Select objects by query
     * @param string $query     The query given
     * @param ?string $category  The category to search in
     * @return array            The result array
     */
    public function selectByQuery(string $query, ?string $category): array {

        $result = [];

        foreach ($this->Repositories as $RepoName => $Repo) {
            if ($category && $RepoName !== $category) {
                continue;
            }

            $unmappedResult = $Repo->selectByKey($query);
            if (count($unmappedResult) > 0) {

                foreach ($unmappedResult as $item) {
                    $mappedObject = $Repo->returnMappedObject(json_decode(json_encode($item), true));

                    $result[] = $this->$RepoName($mappedObject);
                }
            }
        }

        //SORT BY OBJECT ID
        usort($result,function($a,$b){return strcmp($a->ObjectID,$b->ObjectID);});
        return $result;
    }

    /**
     * Map a book object to a generic object
     * @param Book $obj The book object
     * @return GenericObjectResponse The object mapped
     */
    public function Book(Book $obj): GenericObjectResponse {
        $authors = [];
        foreach ($obj->Authors as $author) {
            $authors[] = $author->firstname[0]." ".$author->lastname;
        }

        return new GenericObjectResponse(
            $obj->ObjectID,
            $obj->Title,
            [
                'Publisher' => $obj->Publisher->Name,
                'Year' => $obj->Year,
                'ISBN' => $obj->ISBN,
                'Pages' => $obj->Pages,
                'Authors' => implode(", ", $authors)
            ],
            "Book",
            $obj->Note,
            $obj->Url,
            $obj->Tag
        );
    }

    /**
     * Map a computer object to a generic object
     * @param Computer $obj The computer object
     * @return GenericObjectResponse The object mapped
     */
    public function Computer(Computer $obj): GenericObjectResponse {
        return new GenericObjectResponse(
            $obj->ObjectID,
            $obj->ModelName,
            [
                'Year' => $obj->Year,
                'Hdd size' => $obj->HddSize,
                'Os' => $obj->Os->Name,
                'Cpu' => $obj->Cpu->ModelName . ' ' . $obj->Cpu->Speed,
                'Ram' => $obj->Ram->ModelName . ' ' . $obj->Ram->Size
            ],
            "Computer",
            $obj->Note,
            $obj->Url,
            $obj->Tag
        );
    }

    /**
     * Map a magazine object to a generic object
     * @param Magazine $obj The magazine object
     * @return GenericObjectResponse The object mapped
     */
    public function Magazine(Magazine $obj): GenericObjectResponse {
        return new GenericObjectResponse(
            $obj->ObjectID,
            $obj->Title,
            [
                'Magazine number' => $obj->MagazineNumber,
                'Publisher' => $obj->Publisher->Name,
                'Year' => $obj->Year
            ],
            "Magazine",
            $obj->Note,
            $obj->Url,
            $obj->Tag
        );
    }

    /**
     * Map a peripheral object to a generic object
     * @param Peripheral $obj The peripheral object
     * @return GenericObjectResponse The object mapped
     */
    public function Peripheral(Peripheral $obj): GenericObjectResponse {
        return new GenericObjectResponse(
            $obj->ObjectID,
            $obj->ModelName,
            [
                'Peripheral type' => $obj->PeripheralType->Name
            ],
            "Peripheral",
            $obj->Note,
            $obj->Url,
            $obj->Tag
        );
    }

    /**
     * Map a software object to a generic object
     * @param Software $obj The software object
     * @return GenericObjectResponse The object mapped
     */
    public function Software(Software $obj): GenericObjectResponse {
        return new GenericObjectResponse(
            $obj->ObjectID,
            $obj->Title,
            [
                'Os' => $obj->Os->Name,
                'Software Type' => $obj->SoftwareType->Name,
                'Support Type' => $obj->SupportType->Name
            ],
            "Software",
            $obj->Note,
            $obj->Url,
            $obj->Tag
        );
    }
}
