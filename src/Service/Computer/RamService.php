<?php

declare(strict_types=1);

namespace App\Service\Computer;

use App\Exception\ServiceException;
use App\Model\Computer\Ram;
use App\Repository\Computer\RamRepository;
use App\Exception\RepositoryException;

class RamService {

    public RamRepository $ramRepository;

    public function __construct(RamRepository $ramRepository) {
        $this->ramRepository = $ramRepository;
    }

    /**
     * Insert ram
     * @param Ram $r The ram to save
     * @throws ServiceException If the same ram is already saveed
     * @throws RepositoryException If the save fails
     */
    public function save(Ram $r): void {
        $ram = $this->ramRepository->findByName($r->modelName);
        if ($ram && $ram->size == $r->size)
            throw new ServiceException("Ram name and size already used!");

        $this->ramRepository->save($r);
    }

    /**
     * Select ram by id
     * @param int $id The id to select
     * @return Ram The ram selected
     * @throws ServiceException If not found
     */
    public function findById(int $id): Ram {
        $ram = $this->ramRepository->findById($id);
        if (is_null($ram)) {
            throw new ServiceException("Ram not found");
        }

        return $ram;
    }

    /**
     * Select ram by name
     * @param string $name The name to select
     * @return Ram The Ram selected
     * @throws ServiceException If not found
     */
    public function findByName(string $name): Ram {
        $ram = $this->ramRepository->findByName($name);
        if (is_null($ram)) {
            throw new ServiceException("Ram not found");
        }

        return $ram;
    }

    /**
     * Select ram by key
     * @param string $key The key to search
     * @return array The Rams selected
     */
    public function findByKey(string $key): array {
        return $this->ramRepository->findByKey($key);
    }

    /**
     * Select all
     * @return array All the rams 
     */
    public function findAll(): array {
        return $this->ramRepository->findAll();
    }

    /**
     * Update a ram
     * @param Ram $r The ram to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Ram $r): void {
        $ram = $this->ramRepository->findById($r->id);
        if (is_null($ram)) {
            throw new ServiceException("Ram not found!");
        }

        $this->ramRepository->update($r);
    }

    /**
     * Delete ram
     * @param int $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void {
        $r = $this->ramRepository->findById($id);
        if (is_null($r)) {
            throw new ServiceException("Ram not found!");
        }

        $this->ramRepository->delete($id);
    }
}
