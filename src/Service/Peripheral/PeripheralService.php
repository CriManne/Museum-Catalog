<?php

declare(strict_types=1);

namespace App\Service\Peripheral;

use App\Exception\ServiceException;
use App\Model\Peripheral\Peripheral;
use App\Repository\Peripheral\PeripheralRepository;

class PeripheralService {

    public PeripheralRepository $peripheralRepository;

    public function __construct(PeripheralRepository $peripheralRepository) {
        $this->peripheralRepository = $peripheralRepository;
    }

    /**
     * Insert peripheral
     * @param Peripheral $p The peripheral to insert
     * @return Peripheral The peripheral inserted
     * @throws ServiceException If the ModelName is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(Peripheral $p): Peripheral {
        $peripheral = $this->peripheralRepository->selectByModelName($p->ModelName);
        if ($peripheral)
            throw new ServiceException("Peripheral already used!");

        return $this->peripheralRepository->insert($p);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Peripheral The peripheral selected
     * @throws ServiceException If not found
     */
    public function selectById(string $id): Peripheral {
        $peripheral = $this->peripheralRepository->selectById($id);
        if ($peripheral == null) throw new ServiceException("Peripheral not found");

        return $peripheral;
    }

    /**
     * Select by ModelName
     * @param string $ModelName The ModelName to select
     * @return Peripheral The peripheral selected
     * @throws ServiceException If not found
     */
    public function selectByModelName(string $ModelName): Peripheral {
        $peripheral = $this->peripheralRepository->selectByModelName($ModelName);
        if ($peripheral == null) throw new ServiceException("Peripheral not found");

        return $peripheral;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array The pheriperals selected, empty if no result
     */
    public function selectByKey(string $key):array {
        return $this->peripheralRepository->selectByKey($key);
    }

    /**
     * Update a Peripheral
     * @param Peripheral $p The Peripheral to update
     * @return Peripheral The peripheral updated
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Peripheral $p): Peripheral {
        if ($this->peripheralRepository->selectById($p->ObjectID) == null)
            throw new ServiceException("Peripheral not found!");

        return $this->peripheralRepository->update($p);
    }

    /**
     * Delete a Peripheral
     * @param string $id The id to delete
     * @return Peripheral The peripheral deleted
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): Peripheral {
        $p = $this->peripheralRepository->selectById($id);
        if ($p == null)
            throw new ServiceException("Peripheral not found!");

        $this->peripheralRepository->delete($id);
        return $p;
    }
}
