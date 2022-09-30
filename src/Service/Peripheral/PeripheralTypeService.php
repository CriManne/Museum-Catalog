<?php

declare(strict_types=1);

namespace App\Service\Peripheral;

use App\Exception\ServiceException;
use App\Model\Peripheral\PeripheralType;
use App\Repository\Peripheral\PeripheralTypeRepository;

class PeripheralTypeService {

    public PeripheralTypeRepository $peripheralTypeRepository;

    public function __construct(PeripheralTypeRepository $peripheralTypeRepository) {
        $this->peripheralTypeRepository = $peripheralTypeRepository;
    }

    /**
     * Insert peripheral type
     * @param PeripheralType $pt The object to insert
     * @throws ServiceException If the name is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(PeripheralType $pt): void {
        $pType = $this->peripheralTypeRepository->selectByName($pt->Name);
        if ($pType)
            throw new ServiceException("PeripheralType name already used!");

        $this->peripheralTypeRepository->insert($pt);
    }

    /**
     * Select by id
     * @param int $id The id to select
     * @return PeripheralType The object selected
     * @throws ServiceException If not found
     */
    public function selectById(int $id): PeripheralType {
        $peripheralType = $this->peripheralTypeRepository->selectById($id);
        if ($peripheralType == null) throw new ServiceException("PeripheralType not found");

        return $peripheralType;
    }

    /**
     * Select by name
     * @param string $name The name to select
     * @return PeripheralType The object selected
     * @throws ServiceException If not found
     */
    public function selectByName(string $name): PeripheralType {
        $peripheralType = $this->peripheralTypeRepository->selectByName($name);
        if ($peripheralType == null) throw new ServiceException("PeripheralType not found");

        return $peripheralType;
    }

    /**
     * Select by key
     * @param string $key The key to search
     * @return array The objects selected
     */
    public function selectByKey(string $key): array {
        return $this->peripheralTypeRepository->selectByKey($key);        
    }

    /**
     * Select all
     * @return array All the ptype
     */
    public function selectAll(): array {
        return $this->peripheralTypeRepository->selectAll();
    }

    /**
     * Update peripheral type
     * @param PeripheralType $pt The object to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(PeripheralType $pt): void {
        if ($this->peripheralTypeRepository->selectById($pt->PeripheralTypeID) == null)
            throw new ServiceException("PeripheralType not found!");

        $this->peripheralTypeRepository->update($pt);
    }

    /**
     * Delete PeripheralType
     * @param int $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void {
        $pt = $this->peripheralTypeRepository->selectById($id);
        if ($pt == null)
            throw new ServiceException("PeripheralType not found!");

        $this->peripheralTypeRepository->delete($id);
    }
}
