<?php

declare(strict_types=1);

namespace App\SearchEngine;

use App\Controller\Api\ArtifactsListController;
use App\Controller\Api\ComponentsListController;
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
use App\Plugins\Injection\DIC;
use App\Util\ORM;
use DI\DependencyException;
use DI\NotFoundException;
use ReflectionException;

class ComponentSearchEngine
{
    /**
     * Select generic objects
     *
     * @param string  $category The category to search in
     * @param ?string $query    The eventual query
     *
     * @return array            The result array
     * @throws DependencyException
     * @throws ServiceException
     * @throws ReflectionException
     */
    public function selectGenerics(string $category, ?string $query = null): array
    {
        $result     = [];
        $classFound = false;

        foreach (ArtifactsListController::CATEGORIES as $categoryName) {
            try {
                $artifactService = DIC::getComponentServiceByName($categoryName, $category);
                $classFound      = true;

                $unmappedResult = null;
                if ($query) {
                    $unmappedResult = $artifactService->findByQuery($query);
                } else {
                    $unmappedResult = $artifactService->find();
                }
                if (count($unmappedResult) > 0) {
                    foreach ($unmappedResult as $item) {
                        $result[] = $this->$category($item);
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
     *
     * @param Author $obj The author object
     *
     * @return GenericComponentResponse The object mapped
     */
    public function Author(Author $obj): GenericComponentResponse
    {
        return new GenericComponentResponse(
            $obj->id,
            $obj->firstname . " " . $obj->lastname,
            ComponentsListController::AUTHOR
        );
    }

    /**
     * Map a publisher object to a generic object
     *
     * @param Publisher $obj The publisher object
     *
     * @return GenericComponentResponse The object mapped
     */
    public function Publisher(Publisher $obj): GenericComponentResponse
    {
        return new GenericComponentResponse(
            $obj->id,
            $obj->name,
            ComponentsListController::PUBLISHER
        );
    }

    /**
     * Map a cpu object to a generic object
     *
     * @param Cpu $obj The cpu object
     *
     * @return GenericComponentResponse The object mapped
     */
    public function Cpu(Cpu $obj): GenericComponentResponse
    {
        return new GenericComponentResponse(
            $obj->id,
            $obj->modelName . " " . $obj->speed,
            ComponentsListController::CPU
        );
    }

    /**
     * Map a os object to a generic object
     *
     * @param Os $obj The os object
     *
     * @return GenericComponentResponse The object mapped
     */
    public function Os(Os $obj): GenericComponentResponse
    {
        return new GenericComponentResponse(
            $obj->id,
            $obj->name,
            ComponentsListController::OS
        );
    }

    /**
     * Map a ram object to a generic object
     *
     * @param Ram $obj The ram object
     *
     * @return GenericComponentResponse The object mapped
     */
    public function Ram(Ram $obj): GenericComponentResponse
    {
        return new GenericComponentResponse(
            $obj->id,
            $obj->modelName . " " . $obj->size,
            ComponentsListController::RAM
        );
    }

    /**
     * Map a ptype object to a generic object
     *
     * @param PeripheralType $obj The ptype object
     *
     * @return GenericComponentResponse The object mapped
     */
    public function PeripheralType(PeripheralType $obj): GenericComponentResponse
    {
        return new GenericComponentResponse(
            $obj->id,
            $obj->name,
            ComponentsListController::PERIPHERAL_TYPE
        );
    }

    /**
     * Map a software type object to a generic object
     *
     * @param SoftwareType $obj The software type object
     *
     * @return GenericComponentResponse The object mapped
     */
    public function SoftwareType(SoftwareType $obj): GenericComponentResponse
    {
        return new GenericComponentResponse(
            $obj->id,
            $obj->name,
            ComponentsListController::SOFTWARE_TYPE
        );
    }

    /**
     * Map a support type object to a generic object
     *
     * @param SupportType $obj The support type object
     *
     * @return GenericComponentResponse The object mapped
     */
    public function SupportType(SupportType $obj): GenericComponentResponse
    {
        return new GenericComponentResponse(
            $obj->id,
            $obj->name,
            ComponentsListController::SUPPORT_TYPE
        );
    }
}
