<?php

declare(strict_types=1);

namespace App\Service\Computer;

use App\Exception\ServiceException;
use App\Model\Computer\Computer;
use App\Repository\Computer\ComputerRepository;
use App\Exception\RepositoryException;

class ComputerService {

    public ComputerRepository $computerRepository;

    public function __construct(ComputerRepository $computerRepository) {
        $this->computerRepository = $computerRepository;
    }

    /**
     * Insert computer
     * @param Computer $c The computer to insert
     * @throws ServiceException If the ModelName is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(Computer $c): void {
        $computer = $this->computerRepository->selectByModelName($c->ModelName);
        if ($computer)
            throw new ServiceException("Computer model name already used!");

        $this->computerRepository->insert($c);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Computer The computer selected
     * @throws ServiceException If not found
     */
    public function selectById(string $id): Computer {
        $computer = $this->computerRepository->selectById($id);
        if (is_null($computer)) {
            throw new ServiceException("Computer not found");
        }

        return $computer;
    }

    /**
     * Select by ModelName
     * @param string $ModelName The ModelName to select
     * @return Computer The computer selected
     * @throws ServiceException If not found
     */
    public function selectByModelName(string $ModelName): Computer {
        $computer = $this->computerRepository->selectByModelName($ModelName);
        if (is_null($computer)) {
            throw new ServiceException("Computer not found");
        }

        return $computer;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array The array of computers, empty if no result
     */
    public function selectByKey(string $key): array {
        return $this->computerRepository->selectByKey($key);
    }

    /**
     * Select all
     * @return array All of the computers
     */
    public function selectAll(): array {
        return $this->computerRepository->selectAll();
    }

    /**
     * Update a Computer
     * @param Computer $c The Computer to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Computer $c): void {
        $comp = $this->computerRepository->selectById($c->objectId);
        if (is_null($comp)) {
            throw new ServiceException("Computer not found!");
        }

        $this->computerRepository->update($c);
    }

    /**
     * Delete a Computer
     * @param string $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void {
        $c = $this->computerRepository->selectById($id);
        if (is_null($c)) {
            throw new ServiceException("Computer not found!");
        }

        $this->computerRepository->delete($id);
    }
}
