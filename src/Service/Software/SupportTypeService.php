<?php

declare(strict_types=1);

namespace App\Service\Software;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\ServiceException;
use App\Models\Software\SupportType;
use App\Repository\Software\SupportTypeRepository;
use App\Service\IComponentService;

class SupportTypeService implements IComponentService
{
    public function __construct(
        protected SupportTypeRepository $supportTypeRepository
    )
    {
    }

    /**
     * Insert SupportType
     *
     * @param SupportType $s The SupportType to save
     *
     * @throws ServiceException If the name is already used
     * @throws RepositoryException If the save fails
     */
    public function save(SupportType $s): void
    {
        $sType = $this->supportTypeRepository->findFirst(new FetchParams(
            conditions: "name = :name",
            bind: ["name" => $s->name]
        ));

        if ($sType) {
            throw new ServiceException("Support Type name already used!");
        }

        $this->supportTypeRepository->save($s);
    }

    /**
     * Select by id
     *
     * @param int $id The id to select
     *
     * @return SupportType The SupportType selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findById(int $id): SupportType
    {
        $supportType = $this->supportTypeRepository->findById($id);

        if (!$supportType) {
            throw new ServiceException("Support Type not found");
        }

        return $supportType;
    }

    /**
     * Select by name
     *
     * @param string $name The name to select
     *
     * @return SupportType The SupportType selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findByName(string $name): SupportType
    {
        $supportType = $this->supportTypeRepository->findFirst(new FetchParams(
            conditions: "name = :name",
            bind: ["name" => $name]
        ));

        if (!$supportType) {
            throw new ServiceException("Support Type not found");
        }

        return $supportType;
    }

    /**
     * Select by key
     *
     * @param string $key The key to search
     *
     * @return array The SupportTypes selected
     * @throws RepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->supportTypeRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the support type
     * @throws RepositoryException
     */
    public function find(): array
    {
        return $this->supportTypeRepository->find();
    }

    /**
     * Update SupportType
     *
     * @param SupportType $s The SupportType to update
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(SupportType $s): void
    {
        $supT = $this->supportTypeRepository->findById($s->id);
        if (!$supT) {
            throw new ServiceException("Support Type not found!");
        }

        $this->supportTypeRepository->update($s);
    }

    /**
     * Delete SupportType
     *
     * @param int $id The id to delete
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void
    {
        $supportType = $this->supportTypeRepository->findById($id);
        if (!$supportType) {
            throw new ServiceException("Support Type not found!");
        }

        $this->supportTypeRepository->delete($id);
    }
}
