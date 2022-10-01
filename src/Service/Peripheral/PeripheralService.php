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
     * @throws ServiceException If the ModelName is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(Peripheral $p): void {
        $peripheral = $this->peripheralRepository->selectByModelName($p->ModelName);
        if ($peripheral)
            throw new ServiceException("Peripheral model name already used!");

        $this->peripheralRepository->insert($p);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Peripheral The peripheral selected
     * @throws ServiceException If not found
     */
    public function selectById(string $id): Peripheral {
        $peripheral = $this->peripheralRepository->selectById($id);

        if (is_null($peripheral)) {
            throw new ServiceException("Peripheral not found");
        }

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
        if (is_null($peripheral)) {
            throw new ServiceException("Peripheral not found");
        }

        return $peripheral;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array The pheriperals selected, empty if no result
     */
    public function selectByKey(string $key): array {
        return $this->peripheralRepository->selectByKey($key);
    }

    /**
     * Select all
     * @return array All the pheriperals
     */
    public function selectAll(): array {
        return $this->peripheralRepository->selectAll();
    }

    /**
     * Update a Peripheral
     * @param Peripheral $p The Peripheral to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Peripheral $p): void {
        $per = $this->peripheralRepository->selectById($p->ObjectID);
        if (is_null($per)) {
            throw new ServiceException("Peripheral not found!");
        }

        $this->peripheralRepository->update($p);
    }

    /**
     * Delete a Peripheral
     * @param string $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void {
        $p = $this->peripheralRepository->selectById($id);
        if (is_null($p)) {
            throw new ServiceException("Peripheral not found!");
        }

        $this->peripheralRepository->delete($id);
    }
}
