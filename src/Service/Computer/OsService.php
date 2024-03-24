<?php

declare(strict_types=1);

namespace App\Service\Computer;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use AbstractRepo\Interfaces\IModel;
use App\Exception\ServiceException;
use App\Model\Computer\Os;
use App\Repository\Computer\OsRepository;

class OsService
{
    public OsRepository $osRepository;

    public function __construct(OsRepository $osRepository)
    {
        $this->osRepository = $osRepository;
    }

    /**
     * Insert os
     * @param Os $os The os to save
     * @throws AbstractRepositoryException
     * @throws ServiceException If the os name is already used
     */
    public function save(Os $os): void
    {
        $osFetch = $this->osRepository->findFirst(
            new FetchParams(
                conditions: "name = :name",
                bind: [
                    "name" => $os->name,
                ]
            )
        );
        if ($osFetch) {
            throw new ServiceException("Os name already used!");
        }

        $this->osRepository->save($os);
    }

    /**
     * Select os by id
     * @param int $id The id to select
     * @return Os|IModel The os selected
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function findById(int $id): Os|IModel
    {
        $os = $this->osRepository->findById($id);
        if (is_null($os)) {
            throw new ServiceException("Os not found");
        }

        return $os;
    }

    /**
     * Select os by key
     * @param string $key The key to search
     * @return Os[]|IModel[] The oss selected
     * @throws AbstractRepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->osRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return Os[]|IModel[] All the oss
     * @throws AbstractRepositoryException
     */
    public function find(): array
    {
        return $this->osRepository->find();
    }

    /**
     * Update a os
     * @param Os $os The os to update
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function update(Os $os): void
    {
        $o = $this->osRepository->findById($os->id);
        if (is_null($o)) {
            throw new ServiceException("Os not found!");
        }

        $this->osRepository->update($os);
    }

    /**
     * Delete an os
     * @param int $id The id to delete
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function delete(int $id): void
    {
        $os = $this->osRepository->findById($id);
        if (is_null($os)) {
            throw new ServiceException("Os not found!");
        }

        $this->osRepository->delete($id);
    }
}
