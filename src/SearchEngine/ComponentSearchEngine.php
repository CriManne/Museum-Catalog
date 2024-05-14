<?php

declare(strict_types=1);

namespace App\SearchEngine;

use App\Controller\Api\ArtifactsListController;
use App\DataModels\Response\GenericComponentResponse;
use App\Exception\ServiceException;
use App\Models\Book\Author;
use App\Models\Book\Publisher;
use App\Models\Computer\Cpu;
use App\Models\Computer\Os;
use App\Models\Computer\Ram;
use App\Models\Peripheral\PeripheralType;
use App\Models\Software\SoftwareType;
use App\Models\Software\SupportType;
use App\Util\ORM;
use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;

class ComponentSearchEngine {

    private Container $container;
    private array $categories;

    /**
     * @throws Exception
     */
    public function __construct(
        string $containerPath = "config/container.php"
    ) {
        $builder = new ContainerBuilder();
        $builder->addDefinitions($containerPath);
        $this->container = $builder->build();
        $this->categories = ArtifactsListController::CATEGORIES;
    }

    /**
     * Select specific component by id and category
     * @param int $id The id to select
     * @param string $servicePath The path of the service class to use
     * @return object The object fetched
     * @throws ServiceException If not found
     */
    public function selectSpecificByIdAndCategory(int $id, string $servicePath): object {
        try {

            $componentService = $this->container->get($servicePath);

            return $componentService->findById($id);
        } catch (Exception | ServiceException) {
        }
        throw new ServiceException("Component with id [$id] not found!");
    }

    /**
     * Select generic objects
     * @param string $category The category to search in
     * @param ?string $query The eventual query
     * @return array            The result array
     * @throws DependencyException
     * @throws ServiceException
     * @throws \ReflectionException
     */
    public function selectGenerics(string $category, ?string $query = null): array {
        $result = [];
        $classFound = false;

        foreach ($this->categories as $categoryName) {

            $artifactServicePath = "App\\Service\\$categoryName\\$category" . "Service";

            try {
                $artifactService = $this->container->get($artifactServicePath);
                $classFound = true;

                $classPath = "App\\Model\\$categoryName\\$category";
                $unmappedResult = null;
                if ($query) {
                    $unmappedResult = $artifactService->findByQuery($query);
                } else {
                    $unmappedResult = $artifactService->find();
                }
                if (count($unmappedResult) > 0) {

                    foreach ($unmappedResult as $item) {
                        $mappedObject = ORM::getNewInstance($classPath, (array)$item);

                        $result[] = $this->$category($mappedObject);
                    }
                }
            } catch (NotFoundException) {
            }
        }

        if (!$classFound) {
            throw new ServiceException("Category {$category} not found!");
        }

        //SORT BY OBJECT ID
        usort($result, function ($a, $b) {
            return $a->id - $b->id;
        });
        return $result;
    }

    /**
     * Map a author object to a generic object
     * @param Author $obj The author object
     * @return GenericComponentResponse The object mapped
     */
    public function Author(Author $obj): GenericComponentResponse {
        return new GenericComponentResponse(
            $obj->id,
            $obj->firstname . " " . $obj->lastname,
            "Author"
        );
    }

    /**
     * Map a publisher object to a generic object
     * @param Publisher $obj The publisher object
     * @return GenericComponentResponse The object mapped
     */
    public function Publisher(Publisher $obj): GenericComponentResponse {
        return new GenericComponentResponse(
            $obj->id,
            $obj->name,
            "Publisher"
        );
    }

    /**
     * Map a cpu object to a generic object
     * @param Cpu $obj The cpu object
     * @return GenericComponentResponse The object mapped
     */
    public function Cpu(Cpu $obj): GenericComponentResponse {
        return new GenericComponentResponse(
            $obj->id,
            $obj->modelName . " " . $obj->speed,
            "Cpu"
        );
    }

    /**
     * Map a os object to a generic object
     * @param Os $obj The os object
     * @return GenericComponentResponse The object mapped
     */
    public function Os(Os $obj): GenericComponentResponse {
        return new GenericComponentResponse(
            $obj->id,
            $obj->name,
            "Os"
        );
    }

    /**
     * Map a ram object to a generic object
     * @param Ram $obj The ram object
     * @return GenericComponentResponse The object mapped
     */
    public function Ram(Ram $obj): GenericComponentResponse {
        return new GenericComponentResponse(
            $obj->id,
            $obj->modelName . " " . $obj->size,
            "Ram"
        );
    }

    /**
     * Map a ptype object to a generic object
     * @param PeripheralType $obj The ptype object
     * @return GenericComponentResponse The object mapped
     */
    public function PeripheralType(PeripheralType $obj): GenericComponentResponse {
        return new GenericComponentResponse(
            $obj->id,
            $obj->name,
            "PeripheralType"
        );
    }

    /**
     * Map a softtype object to a generic object
     * @param SoftwareType $obj The softtype object
     * @return GenericComponentResponse The object mapped
     */
    public function SoftwareType(SoftwareType $obj): GenericComponentResponse {
        return new GenericComponentResponse(
            $obj->id,
            $obj->name,
            "SoftwareType"
        );
    }

    /**
     * Map a suptype object to a generic object
     * @param SupportType $obj The suptype object
     * @return GenericComponentResponse The object mapped
     */
    public function SupportType(SupportType $obj): GenericComponentResponse {
        return new GenericComponentResponse(
            $obj->id,
            $obj->name,
            "SupportType"
        );
    }
}
