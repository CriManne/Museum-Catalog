<?php

declare(strict_types=1);

namespace App\SearchEngine;

use App\Controller\Api\ArtifactsListController;
use App\DataModels\Response\GenericArtifactResponse;
use App\Exception\ServiceException;
use App\Models\Book\Book;
use App\Models\Computer\Computer;
use App\Models\Magazine\Magazine;
use App\Models\Peripheral\Peripheral;
use App\Models\Software\Software;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;

class ArtifactSearchEngine
{

    private Container $container;
    private array     $categories;

    /**
     * @throws Exception
     */
    public function __construct(
        string $containerPath = "config/container.php"
    )
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions($containerPath);
        $this->container  = $builder->build();
        $this->categories = ArtifactsListController::CATEGORIES;
    }

    /**
     * Select specific object by id and category
     *
     * @param string $objectId The id to select
     * @param string $category The category to search in
     *
     * @return object The object fetched
     * @throws ServiceException If no object is found
     */
    public function selectSpecificByIdAndCategory(string $objectId, string $category): object
    {
        try {
            $artifactServicePath = "App\\Service\\$category\\$category" . "Service";

            $artifactService = $this->container->get($artifactServicePath);

            return $artifactService->findById($objectId);
        } catch (Exception|ServiceException) {
        }
        throw new ServiceException("Artifact with id [$objectId] in category [$category] not found!");
    }


    /**
     * Select generic object by id
     *
     * @param string $objectId The objectId to select
     *
     * @return GenericArtifactResponse            The Object selected
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ServiceException If not found
     */
    public function selectGenericById(string $objectId): GenericArtifactResponse
    {
        foreach ($this->categories as $categoryName) {

            $artifactServicePath = "App\\Service\\$categoryName\\$categoryName" . "Service";

            $artifactService = $this->container->get($artifactServicePath);

            try {
                $result = $artifactService->findById($objectId);

                return $this->$categoryName($result);
            } catch (ServiceException) {
            }
        }
        throw new ServiceException("Artifact with id [$objectId] not found!");
    }

    /**
     * Select generics objects
     *
     * @param ?string $category The category to search in
     * @param ?string $query    The eventual query
     *
     * @return array            The result array
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function selectGenerics(?string $category = null, ?string $query = null): array
    {
        $result = [];

        foreach ($this->categories as $categoryName) {
            if ($category && $categoryName !== $category) {
                continue;
            }

            $artifactServicePath = "App\\Service\\$categoryName\\$categoryName" . "Service";

            $artifactService = $this->container->get($artifactServicePath);
            
            $unmappedResult = null;
            if ($query) {
                $unmappedResult = $artifactService->findByQuery($query);
            } else {
                $unmappedResult = $artifactService->find();
            }

            if (count($unmappedResult) > 0) {
                foreach ($unmappedResult as $item) {
                    $result[] = $this->$categoryName($item);
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
     *
     * @param Book $obj The book object
     *
     * @return GenericArtifactResponse The object mapped
     */
    public function Book(Book $obj): GenericArtifactResponse
    {
        $authors = [];
//        if ($obj->authors) {
//            foreach ($obj->authors as $author) {
//                $authors[] = $author->firstname[0] . " " . $author->lastname;
//            }
//        }

        return new GenericArtifactResponse(
            objectId: $obj->genericObject->id,
            title: $obj->title,
            descriptors: [
                'Publisher' => $obj->publisher->name,
                'Year'      => $obj->year,
                'ISBN'      => $obj->isbn ?? "-",
                'Pages'     => $obj->pages ?? "-",
                'Authors'   => count($authors) > 0 ? implode(", ", $authors) : "Unknown"
            ],
            category: "Book",
            note: $obj->genericObject->note,
            url: $obj->genericObject->url,
            tag: $obj->genericObject->tag
        );
    }

    /**
     * Map a computer object to a generic object
     *
     * @param Computer $obj The computer object
     *
     * @return GenericArtifactResponse The object mapped
     */
    public function Computer(Computer $obj): GenericArtifactResponse
    {

        $description = [
            'Year' => $obj->year,
            'Cpu'  => $obj->cpu->modelName . ' ' . $obj->cpu->speed,
            'Ram'  => $obj->ram->modelName . ' ' . $obj->ram->size
        ];

        if (isset($obj->hddSize)) {
            $description["Hdd size"] = $obj->hddSize;
        }

        if (isset($obj->os)) {
            $description["Os"] = $obj->os->name;
        }

        return new GenericArtifactResponse(
            objectId: $obj->genericObject->id,
            title: $obj->modelName,
            descriptors: $description,
            category: "Computer",
            note: $obj->genericObject->note,
            url: $obj->genericObject->url,
            tag: $obj->genericObject->tag
        );
    }

    /**
     * Map a magazine object to a generic object
     *
     * @param Magazine $obj The magazine object
     *
     * @return GenericArtifactResponse The object mapped
     */
    public function Magazine(Magazine $obj): GenericArtifactResponse
    {
        return new GenericArtifactResponse(
            objectId: $obj->genericObject->id,
            title: $obj->title,
            descriptors: [
                'Magazine number' => $obj->magazineNumber,
                'Publisher'       => $obj->publisher->name,
                'Year'            => $obj->year
            ],
            category: "Magazine",
            note: $obj->genericObject->note,
            url: $obj->genericObject->url,
            tag: $obj->genericObject->tag
        );
    }

    /**
     * Map a peripheral object to a generic object
     *
     * @param Peripheral $obj The peripheral object
     *
     * @return GenericArtifactResponse The object mapped
     */
    public function Peripheral(Peripheral $obj): GenericArtifactResponse
    {
        return new GenericArtifactResponse(
            objectId: $obj->genericObject->id,
            title: $obj->modelName,
            descriptors: [
                'Peripheral type' => $obj->peripheralType->name
            ],
            category: "Peripheral",
            note: $obj->genericObject->note,
            url: $obj->genericObject->url,
            tag: $obj->genericObject->tag
        );
    }

    /**
     * Map a software object to a generic object
     *
     * @param Software $obj The software object
     *
     * @return GenericArtifactResponse The object mapped
     */
    public function Software(Software $obj): GenericArtifactResponse
    {
        return new GenericArtifactResponse(
            objectId: $obj->genericObject->id,
            title: $obj->title,
            descriptors: [
                'Os'            => $obj->os->name,
                'Software Type' => $obj->softwareType->name,
                'Support Type'  => $obj->supportType->name
            ],
            category: "Software",
            note: $obj->genericObject->note,
            url: $obj->genericObject->url,
            tag: $obj->genericObject->tag
        );
    }
}
