<?php

declare(strict_types=1);

namespace App\Service\Computer;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use AbstractRepo\Interfaces\IModel;
use App\Exception\ServiceException;
use App\Model\Computer\Ram;
use App\Repository\Computer\RamRepository;

class RamService
{
    public RamRepository $ramRepository;

    public function __construct(RamRepository $ramRepository)
    {
        $this->ramRepository = $ramRepository;
    }

    /**
     * Insert ram
     * @param Ram $r The ram to save
     * @throws AbstractRepositoryException
     * @throws ServiceException If the same ram is already saved
     */
    public function save(Ram $r): void
    {
        $ram = $this->ramRepository->findFirst(
            new FetchParams(
                conditions: "modelName = :modelName",
                bind: [
                    "modelName" => $r->modelName,
                ]
            )
        );
        if ($ram && $ram->size == $r->size) {
            throw new ServiceException("Ram name and size already used!");
        }

        $this->ramRepository->save($r);
    }

    /**
     * Select ram by id
     * @param int $id The id to select
     * @return Ram|IModel The ram selected
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function findById(int $id): Ram|IModel
    {
        $ram = $this->ramRepository->findById($id);
        if (is_null($ram)) {
            throw new ServiceException("Ram not found");
        }

        return $ram;
    }

    /**
     * Select ram by key
     * @param string $key The key to search
     * @return Ram[]|IModel[] The Rams selected
     * @throws AbstractRepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->ramRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return Ram[]|IModel[] All the rams
     * @throws AbstractRepositoryException
     */
    public function find(): array
    {
        return $this->ramRepository->find();
    }

    /**
     * Update a ram
     * @param Ram $r The ram to update
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function update(Ram $r): void
    {
        $ram = $this->ramRepository->findById($r->id);
        if (is_null($ram)) {
            throw new ServiceException("Ram not found!");
        }

        $this->ramRepository->update($r);
    }

    /**
     * Delete ram
     * @param int $id The id to delete
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function delete(int $id): void
    {
        $r = $this->ramRepository->findById($id);
        if (is_null($r)) {
            throw new ServiceException("Ram not found!");
        }

        $this->ramRepository->delete($id);
    }
}
