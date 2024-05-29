<?php

declare(strict_types=1);

namespace App\Service\Peripheral;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\ServiceException;
use App\Models\Peripheral\PeripheralType;
use App\Repository\Peripheral\PeripheralTypeRepository;
use App\Service\IComponentService;

class PeripheralTypeService implements IComponentService
{
    public function __construct(
        protected PeripheralTypeRepository $peripheralTypeRepository
    )
    {
    }

    /**
     * Insert peripheral type
     *
     * @param PeripheralType $pt The object to save
     *
     * @throws ServiceException If the name is already used
     * @throws RepositoryException If the save fails
     */
    public function save(PeripheralType $pt): void
    {
        $pType = $this->peripheralTypeRepository->findFirst(
            new FetchParams(
                conditions: "name = :name",
                bind: [
                    "name" => $pt->name,
                ]
            )
        );

        if ($pType) {
            throw new ServiceException("PeripheralType name already used!");
        }

        $this->peripheralTypeRepository->save($pt);
    }

    /**
     * Select by id
     *
     * @param int $id The id to select
     *
     * @return PeripheralType The object selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findById(int $id): PeripheralType
    {
        $peripheralType = $this->peripheralTypeRepository->findById($id);

        if (!$peripheralType) {
            throw new ServiceException("PeripheralType not found");
        }

        return $peripheralType;
    }

    /**
     * Select by name
     *
     * @param string $name The name to select
     *
     * @return PeripheralType The object selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findByName(string $name): PeripheralType
    {
        $peripheralType = $this->peripheralTypeRepository->findFirst(
            new FetchParams(
                conditions: "name = :name",
                bind: [
                    "name" => $name,
                ]
            )

        );

        if (!$peripheralType) {
            throw new ServiceException("PeripheralType not found");
        }

        return $peripheralType;
    }

    /**
     * Select by key
     *
     * @param string $key The key to search
     *
     * @return array The objects selected
     * @throws RepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->peripheralTypeRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the peripheral types
     * @throws RepositoryException
     */
    public function find(): array
    {
        return $this->peripheralTypeRepository->find();
    }

    /**
     * Update peripheral type
     *
     * @param PeripheralType $pt The object to update
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(PeripheralType $pt): void
    {
        $periT = $this->peripheralTypeRepository->findById($pt->id);

        if (!$periT) {
            throw new ServiceException("PeripheralType not found!");
        }

        $this->peripheralTypeRepository->update($pt);
    }

    /**
     * Delete PeripheralType
     *
     * @param int $id The id to delete
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void
    {
        $pt = $this->peripheralTypeRepository->findById($id);
        if (!$pt) {
            throw new ServiceException("PeripheralType not found!");
        }

        $this->peripheralTypeRepository->delete($id);
    }
}
