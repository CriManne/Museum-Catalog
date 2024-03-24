<?php

declare(strict_types=1);

namespace App\Service\Peripheral;

use App\Exception\ServiceException;
use App\Model\Peripheral\PeripheralType;
use App\Repository\Peripheral\PeripheralTypeRepository;
use App\Exception\RepositoryException;

class PeripheralTypeService {

    public PeripheralTypeRepository $peripheralTypeRepository;

    public function __construct(PeripheralTypeRepository $peripheralTypeRepository) {
        $this->peripheralTypeRepository = $peripheralTypeRepository;
    }

    /**
     * Insert peripheral type
     * @param PeripheralType $pt The object to save
     * @throws ServiceException If the name is already used
     * @throws RepositoryException If the save fails
     */
    public function save(PeripheralType $pt): void {
        $pType = $this->peripheralTypeRepository->findByName($pt->name);
        if ($pType)
            throw new ServiceException("PeripheralType name already used!");

        $this->peripheralTypeRepository->save($pt);
    }

    /**
     * Select by id
     * @param int $id The id to select
     * @return PeripheralType The object selected
     * @throws ServiceException If not found
     */
    public function findById(int $id): PeripheralType {
        $peripheralType = $this->peripheralTypeRepository->findById($id);
        if (is_null($peripheralType)) {
            throw new ServiceException("PeripheralType not found");
        }

        return $peripheralType;
    }

    /**
     * Select by name
     * @param string $name The name to select
     * @return PeripheralType The object selected
     * @throws ServiceException If not found
     */
    public function findByName(string $name): PeripheralType {
        $peripheralType = $this->peripheralTypeRepository->findByName($name);
        if (is_null($peripheralType)) {
            throw new ServiceException("PeripheralType not found");
        }

        return $peripheralType;
    }

    /**
     * Select by key
     * @param string $key The key to search
     * @return array The objects selected
     */
    public function findByKey(string $key): array {
        return $this->peripheralTypeRepository->findByKey($key);
    }

    /**
     * Select all
     * @return array All the ptype
     */
    public function findAll(): array {
        return $this->peripheralTypeRepository->findAll();
    }

    /**
     * Update peripheral type
     * @param PeripheralType $pt The object to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(PeripheralType $pt): void {
        $periT = $this->peripheralTypeRepository->findById($pt->id);
        if (is_null($periT)) {
            throw new ServiceException("PeripheralType not found!");
        }

        $this->peripheralTypeRepository->update($pt);
    }

    /**
     * Delete PeripheralType
     * @param int $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void {
        $pt = $this->peripheralTypeRepository->findById($id);
        if (is_null($pt)) {
            throw new ServiceException("PeripheralType not found!");
        }

        $this->peripheralTypeRepository->delete($id);
    }
}
