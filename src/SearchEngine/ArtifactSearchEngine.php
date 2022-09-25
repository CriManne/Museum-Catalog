<?php

declare(strict_types=1);

namespace App\SearchEngine;

use App\Controller\Api\ArtifactsListController;
use App\Exception\ServiceException;
use PDO;
use App\Model\Software\Software;
use App\Model\Book\Book;
use App\Model\Magazine\Magazine;
use App\Model\Peripheral\Peripheral;
use App\Model\Computer\Computer;
use App\Model\Response\GenericArtifactResponse;

use DI\Container;
use DI\ContainerBuilder;

class ArtifactSearchEngine
{

    private Container $container;
    private array $categories;

    public function __construct(
        string $containerPath = "config/container.php"
    ) {
        $builder = new ContainerBuilder();
        $builder->addDefinitions($containerPath);
        $this->container = $builder->build();
        $this->categories = ArtifactsListController::$categories;
    }

    /**
     * Select an object
     * @param string $ObjectID     The ObjectID to select
     * @return ?GenericArtifactResponse            The Object selected, null if not found
     */
    public function selectById(string $ObjectID): ?GenericArtifactResponse
    {
        foreach ($this->categories as $categoryName) {

            $artifactServicePath = "App\\Service\\$categoryName\\$categoryName" . "Service";

            $artifactService = $this->container->get($artifactServicePath);

            $result = null;

            try {
                $result = $artifactService->selectById($ObjectID);
            } catch (ServiceException) {
            }
            if ($result) {
                return $this->$categoryName($result);
            }
        }
        throw new ServiceException("Artifact with id [$ObjectID] not found!");
    }

    /**
     * Select objects
     * @param ?string $category  The category to search in
     * @param ?string $query The eventual query
     * @return array            The result array
     */
    public function select(?string $category = null, ?string $query = null): array
    {
        $result = [];

        foreach ($this->categories as $categoryName) {
            if ($category && $categoryName !== $category) {
                continue;
            }

            $artifactServicePath = "App\\Service\\$categoryName\\$categoryName" . "Service";

            $artifactService = $this->container->get($artifactServicePath);

            $artifactRepoName = strtolower($categoryName) . "Repository";

            $unmappedResult = null;
            if ($query) {
                $unmappedResult = $artifactService->selectByKey($query);
            } else {
                $unmappedResult = $artifactService->selectAll();
            }
            if (count($unmappedResult) > 0) {

                foreach ($unmappedResult as $item) {
                    $mappedObject = $artifactService->$artifactRepoName->returnMappedObject(json_decode(json_encode($item), true));

                    $result[] = $this->$categoryName($mappedObject);
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
     * @return GenericArtifactResponse The object mapped
     */
    public function Book(Book $obj): GenericArtifactResponse
    {
        $authors = [];
        foreach ($obj->Authors as $author) {
            $authors[] = $author->firstname[0] . " " . $author->lastname;
        }

        return new GenericArtifactResponse(
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
     * @return GenericArtifactResponse The object mapped
     */
    public function Computer(Computer $obj): GenericArtifactResponse
    {
        return new GenericArtifactResponse(
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
     * @return GenericArtifactResponse The object mapped
     */
    public function Magazine(Magazine $obj): GenericArtifactResponse
    {
        return new GenericArtifactResponse(
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
     * @return GenericArtifactResponse The object mapped
     */
    public function Peripheral(Peripheral $obj): GenericArtifactResponse
    {
        return new GenericArtifactResponse(
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
     * @return GenericArtifactResponse The object mapped
     */
    public function Software(Software $obj): GenericArtifactResponse
    {
        return new GenericArtifactResponse(
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
