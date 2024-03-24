<?php

declare(strict_types=1);

namespace App\Service\Computer;

use App\Exception\ServiceException;
use App\Model\Computer\Os;
use App\Repository\Computer\OsRepository;
use App\Exception\RepositoryException;

class OsService {

    public OsRepository $osRepository;

    public function __construct(OsRepository $osRepository) {
        $this->osRepository = $osRepository;
    }

    /**
     * Insert os
     * @param Os $os The os to insert
     * @throws ServiceException If the os name is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(Os $os): void {
        $osFetch = $this->osRepository->selectByName($os->name);
        if ($osFetch)
            throw new ServiceException("Os name already used!");

        $this->osRepository->insert($os);
    }

    /**
     * Select os by id
     * @param int $id The id to select
     * @return Os The os selected
     * @throws ServiceException If not found
     */
    public function findById(int $id): Os {
        $os = $this->osRepository->findById($id);
        if (is_null($os)) {
            throw new ServiceException("Os not found");
        }

        return $os;
    }

    /**
     * Select os by name
     * @param string $name The name to select
     * @return Os The os selected
     * @throws ServiceException If not found
     */
    public function selectByName(string $name): Os {
        $os = $this->osRepository->selectByName($name);
        if (is_null($os)) {
            throw new ServiceException("Os not found");
        }

        return $os;
    }

    /**
     * Select os by key
     * @param string $key The key to search
     * @return array The oss selected
     */
    public function findByKey(string $key): array {
        return $this->osRepository->findByKey($key);
    }

    /**
     * Select all
     * @return array All the oss
     */
    public function findAll(): array {
        return $this->osRepository->findAll();
    }

    /**
     * Update a os
     * @param Os $os The os to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Os $os): void {
        $o = $this->osRepository->findById($os->id);
        if (is_null($o)) {
            throw new ServiceException("Os not found!");
        }

        $this->osRepository->update($os);
    }

    /**
     * Delete an os
     * @param int $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void {
        $os = $this->osRepository->findById($id);
        if (is_null($os)) {
            throw new ServiceException("Os not found!");
        }

        $this->osRepository->delete($id);
    }
}
