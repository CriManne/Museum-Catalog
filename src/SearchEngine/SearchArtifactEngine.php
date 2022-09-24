<?php

declare(strict_types=1);

namespace App\SearchEngine;

use App\Controller\Api\CategoriesController;
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
use DI\Container;
use DI\ContainerBuilder;
use DI\NotFoundException;

class SearchArtifactEngine
{

    private Container $container;
    private array $repositories;

    public function __construct(
        ContainerBuilder $builder
    ) {
        $builder->addDefinitions("config/container.php");
        $this->container = $builder->build();
        $this->repositories = CategoriesController::$categories;
    }

    /**
     * Select an object
     * @param string $ObjectID     The ObjectID to select
     * @return ?GenericObjectResponse            The Object selected, null if not found
     */
    public function selectById(string $ObjectID): ?GenericObjectResponse
    {
        foreach ($this->repositories as $repoName) {

            $artifactServicePath = "App\\Service\\$repoName\\$repoName" . "Service";

            $artifactService = $this->container->get($artifactServicePath);

            $result = $artifactService->selectById($ObjectID);

            if ($result) {
                return $this->$repoName($result);
            }
        }
        return null;
    }

    /**
     * Select objects
     * @param ?string $category  The category to search in
     * @param ?string $query The eventual query
     * @return array            The result array
     */
    public function select(?string $category,?string $query=null): array
    {
        $result = [];

        foreach ($this->repositories as $repoName) {
            if ($category && $repoName !== $category) {
                continue;
            }

            $artifactServicePath = "App\\Service\\$repoName\\$repoName" . "Service";

            $artifactService = $this->container->get($artifactServicePath); 

            $artifactRepoName = strtolower($repoName)."Repository";

            $unmappedResult = null;
            if($query){
                $unmappedResult = $artifactService->selectByQuery($query);
            }else{
                $unmappedResult = $artifactService->selectAll();
            }
            if (count($unmappedResult) > 0) {

                foreach ($unmappedResult as $item) {
                    $mappedObject = $artifactService->$artifactRepoName->returnMappedObject(json_decode(json_encode($item), true));

                    $result[] = $this->$repoName($mappedObject);
                }
            }
        }

        //SORT BY OBJECT ID
        usort($result, function ($a, $b) {
            return strcmp($a->ObjectID, $b->ObjectID);
        });
        return $result;
    }

    /**
     * Map a book object to a generic object
     * @param Book $obj The book object
     * @return GenericObjectResponse The object mapped
     */
    public function Book(Book $obj): GenericObjectResponse
    {
        $authors = [];
        foreach ($obj->Authors as $author) {
            $authors[] = $author->firstname[0] . " " . $author->lastname;
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
    public function Computer(Computer $obj): GenericObjectResponse
    {
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
    public function Magazine(Magazine $obj): GenericObjectResponse
    {
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
    public function Peripheral(Peripheral $obj): GenericObjectResponse
    {
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
    public function Software(Software $obj): GenericObjectResponse
    {
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
