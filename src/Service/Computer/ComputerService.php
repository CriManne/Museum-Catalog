<?php

declare(strict_types=1);

namespace App\Service\Computer;

use App\Exception\ServiceException;
use App\Model\Computer\Computer;
use App\Repository\Computer\ComputerRepository;

class ComputerService {

    public ComputerRepository $computerRepository;

    public function __construct(ComputerRepository $computerRepository) {
        $this->computerRepository = $computerRepository;
    }

    /**
     * Insert computer
     * @param Computer $c The computer to insert
     * @return Computer The computer inserted
     * @throws ServiceException If the ModelName is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(Computer $c): Computer {
        if ($this->computerRepository->selectByModelName($c->ModelName) != null)
            throw new ServiceException("Computer already used!");

        return $this->computerRepository->insert($c);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Computer The computer selected
     * @throws ServiceException If not found
     */
    public function selectById(string $id): Computer {
        $computer = $this->computerRepository->selectById($id);
        if ($computer == null) throw new ServiceException("Computer not found");

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
        if ($computer == null) throw new ServiceException("Computer not found");

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
     * Update a Computer
     * @param Computer $c The Computer to update
     * @return Computer The computer updated
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Computer $c): Computer {
        if ($this->computerRepository->selectById($c->ObjectID) == null)
            throw new ServiceException("Computer not found!");

        return $this->computerRepository->update($c);
    }

    /**
     * Delete a Computer
     * @param string $id The id to delete
     * @return Computer The computer deleted
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): Computer {
        $c = $this->computerRepository->selectById($id);
        if ($c == null)
            throw new ServiceException("Computer not found!");

        $this->computerRepository->delete($id);
        return $c;
    }
}
