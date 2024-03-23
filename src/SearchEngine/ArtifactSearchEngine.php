<?php

declare(strict_types=1);

namespace App\SearchEngine;

use App\Controller\Api\ArtifactsListController;
use App\DataModels\Response\GenericArtifactResponse;
use App\Exception\ServiceException;
use App\Model\Book\Book;
use App\Model\Computer\Computer;
use App\Model\Magazine\Magazine;
use App\Model\Peripheral\Peripheral;
use App\Model\Software\Software;
use DI\Container;
use DI\ContainerBuilder;
use Exception;

class ArtifactSearchEngine {

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
     * Select specific object by id and category
     * @param string $objectId The id to select
     * @param string $category The category to search in
     * @return object The object fetched
     * @throws ServiceException If no object is found
     */
    public function selectSpecificByIdAndCategory(string $objectId, string $category): object {
        try {
            $artifactServicePath = "App\\Service\\$category\\$category" . "Service";

            $artifactService = $this->container->get($artifactServicePath);

            return $artifactService->selectById($objectId);
        } catch (Exception | ServiceException) {
        }
        throw new ServiceException("Artifact with id [$objectId] in category [$category] not found!");
    }


    /**
     * Select generic object by id
     * @param string $objectId     The objectId to select
     * @return GenericArtifactResponse            The Object selected
     * @throws ServiceException If not found
     */
    public function selectGenericById(string $objectId): GenericArtifactResponse {
        foreach ($this->categories as $categoryName) {

            $artifactServicePath = "App\\Service\\$categoryName\\$categoryName" . "Service";

            $artifactService = $this->container->get($artifactServicePath);

            try {
                $result = $artifactService->selectById($objectId);

                return $this->$categoryName($result);
            } catch (ServiceException) {
            }
        }
        throw new ServiceException("Artifact with id [$objectId] not found!");
    }

    /**
     * Select generics objects
     * @param ?string $category  The category to search in
     * @param ?string $query The eventual query
     * @return array            The result array
     */
    public function selectGenerics(?string $category = null, ?string $query = null): array {
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
            return strcmp($a->objectId, $b->objectId);
        });
        return $result;
    }

    /**
     * Map a book object to a generic object
     * @param Book $obj The book object
     * @return GenericArtifactResponse The object mapped
     */
    public function Book(Book $obj): GenericArtifactResponse {
        $authors = [];
        if ($obj->authors) {
            foreach ($obj->authors as $author) {
                $authors[] = $author->firstname[0] . " " . $author->lastname;
            }
        }

        return new GenericArtifactResponse(
            $obj->objectId,
            $obj->title,
            [
                'Publisher' => $obj->publisher->name,
                'Year' => $obj->year,
                'ISBN' => $obj->isbn ?? "-",
                'Pages' => $obj->pages ?? "-",
                'Authors' => count($authors) > 0 ? implode(", ", $authors) : "Unknown"
            ],
            "Book",
            $obj->note,
            $obj->url,
            $obj->tag
        );
    }

    /**
     * Map a computer object to a generic object
     * @param Computer $obj The computer object
     * @return GenericArtifactResponse The object mapped
     */
    public function Computer(Computer $obj): GenericArtifactResponse {

        $description = [
            'Year' => $obj->year,
            'Cpu' => $obj->cpu->modelName . ' ' . $obj->cpu->speed,
            'Ram' => $obj->ram->modelName . ' ' . $obj->ram->size
        ];

        if(isset($obj->HddSize)){
            $description["Hdd size"] = $obj->HddSize;
        }

        if(isset($obj->Os)){
            $description["Os"] = $obj->Os->Name;
        }

        return new GenericArtifactResponse(
            $obj->objectId,
            $obj->modelName,
            $description,
            "Computer",
            $obj->note,
            $obj->url,
            $obj->tag
        );
    }

    /**
     * Map a magazine object to a generic object
     * @param Magazine $obj The magazine object
     * @return GenericArtifactResponse The object mapped
     */
    public function Magazine(Magazine $obj): GenericArtifactResponse {
        return new GenericArtifactResponse(
            $obj->objectId,
            $obj->title,
            [
                'Magazine number' => $obj->magazineNumber,
                'Publisher' => $obj->publisher->name,
                'Year' => $obj->year
            ],
            "Magazine",
            $obj->note,
            $obj->url,
            $obj->tag
        );
    }

    /**
     * Map a peripheral object to a generic object
     * @param Peripheral $obj The peripheral object
     * @return GenericArtifactResponse The object mapped
     */
    public function Peripheral(Peripheral $obj): GenericArtifactResponse {
        return new GenericArtifactResponse(
            $obj->objectId,
            $obj->modelName,
            [
                'Peripheral type' => $obj->peripheralType->name
            ],
            "Peripheral",
            $obj->note,
            $obj->url,
            $obj->tag
        );
    }

    /**
     * Map a software object to a generic object
     * @param Software $obj The software object
     * @return GenericArtifactResponse The object mapped
     */
    public function Software(Software $obj): GenericArtifactResponse {
        return new GenericArtifactResponse(
            $obj->objectId,
            $obj->title,
            [
                'Os' => $obj->os->name,
                'Software Type' => $obj->softwareType->name,
                'Support Type' => $obj->supportType->name
            ],
            "Software",
            $obj->note,
            $obj->url,
            $obj->tag
        );
    }
}
