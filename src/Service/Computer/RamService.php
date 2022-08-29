<?php

declare(strict_types=1);

namespace App\Service\Computer;

use App\Exception\ServiceException;
use App\Model\Computer\Ram;
use App\Repository\Computer\RamRepository;

class RamService {

    public RamRepository $ramRepository;

    public function __construct(RamRepository $ramRepository) {
        $this->ramRepository = $ramRepository;
    }

    /**
     * Insert ram
     * @param Ram $r The ram to insert
     * @return Ram The ram inserted
     * @throws ServiceException If the same ram is already inserted
     * @throws RepositoryException If the insert fails
     */
    public function insert(Ram $r): Ram {
        $ram = $this->ramRepository->selectById($r->RamID);
        if ($ram->ModelName == $r->ModelName && $ram->Size == $r->Size)
            throw new ServiceException("Ram name and size already used!");

        return $this->ramRepository->insert($r);
    }

    /**
     * Select ram by id
     * @param int $id The id to select
     * @return Ram The ram selected
     * @throws ServiceException If not found
     */
    public function selectById(int $id): Ram {
        $ram = $this->ramRepository->selectById($id);
        if ($ram == null) throw new ServiceException("Ram not found");

        return $ram;
    }

    /**
     * Select ram by name
     * @param string $name The name to select
     * @return Ram The Ram selected
     * @throws ServiceException If not found
     */
    public function selectByName(string $name): Ram {
        $ram = $this->ramRepository->selectByName($name);
        if ($ram == null) throw new ServiceException("Ram not found");

        return $ram;
    }

    /**
     * Update a ram
     * @param Ram $r The ram to update
     * @return Ram The ram updated
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Ram $r): Ram {
        if ($this->ramRepository->selectById($r->RamID) == null)
            throw new ServiceException("Ram not found!");

        return $this->ramRepository->update($r);
    }

    /**
     * Delete ram
     * @param int $id The id to delete
     * @return Ram The ram deleted
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): Ram {
        $r = $this->ramRepository->selectById($id);
        if ($r == null)
            throw new ServiceException("Ram not found!");

        $this->ramRepository->delete($id);
        return $r;
    }
}
