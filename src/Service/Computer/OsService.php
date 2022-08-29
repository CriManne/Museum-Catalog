<?php

declare(strict_types=1);

namespace App\Service\Computer;

use App\Exception\ServiceException;
use App\Model\Computer\Os;
use App\Repository\Computer\OsRepository;

class OsService {

    public OsRepository $osRepository;

    public function __construct(OsRepository $osRepository) {
        $this->osRepository = $osRepository;
    }

    /**
     * Insert os
     * @param Os $os The os to insert
     * @return Os The os inserted
     * @throws ServiceException If the os name is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(Os $os): Os {
        if ($this->osRepository->selectByName($os->Name) != null)
            throw new ServiceException("Os name already used!");

        return $this->osRepository->insert($os);
    }

    /**
     * Select os by id
     * @param int $id The id to select
     * @return Os The os selected
     * @throws ServiceException If not found
     */
    public function selectById(int $id): Os {
        $os = $this->osRepository->selectById($id);
        if ($os == null) throw new ServiceException("Os not found");

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
        if ($os == null) throw new ServiceException("Os not found");

        return $os;
    }

    /**
     * Update a os
     * @param Os $os The os to update
     * @return Os The os updated
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Os $os): Os {
        if ($this->osRepository->selectById($os->OsID) == null)
            throw new ServiceException("Os not found!");

        return $this->osRepository->update($os);
    }

    /**
     * Delete an os
     * @param int $id The id to delete
     * @return Os The os deleted
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): Os {
        $os = $this->osRepository->selectById($id);
        if ($os == null)
            throw new ServiceException("Os not found!");

        $this->osRepository->delete($id);
        return $os;
    }
}
