<?php

declare(strict_types=1);

namespace App\Service\Software;

use AbstractRepo\DataModels\FetchParams;
use App\Exception\ServiceException;
use App\Models\Software\SoftwareType;
use App\Repository\Software\SoftwareTypeRepository;
use App\Exception\RepositoryException;

class SoftwareTypeService
{
    public SoftwareTypeRepository $softwareTypeRepository;

    public function __construct(SoftwareTypeRepository $softwareTypeRepository)
    {
        $this->softwareTypeRepository = $softwareTypeRepository;
    }

    /**
     * Insert SoftwareType
     * @param SoftwareType $s The SoftwareType to save
     * @throws ServiceException If the name is already used
     * @throws \AbstractRepo\Exceptions\RepositoryException If the save fails
     */
    public function save(SoftwareType $s): void
    {
        $softwareType = $this->softwareTypeRepository->findFirst(new FetchParams(
            conditions: "name = :name",
            bind: [
                "name" => $s->name
            ]
        ));

        if ($softwareType) {
            throw new ServiceException("Software Type name already used!");
        }

        $this->softwareTypeRepository->save($s);
    }

    /**
     * Select by id
     * @param int $id The id to select
     * @return SoftwareType The SoftwareType selected
     * @throws ServiceException If not found
     * @throws \AbstractRepo\Exceptions\RepositoryException
     */
    public function findById(int $id): SoftwareType
    {
        $softwareType = $this->softwareTypeRepository->findById($id);
        if (is_null($softwareType)) {
            throw new ServiceException("Software Type not found");
        }

        return $softwareType;
    }

    /**
     * Select by name
     * @param string $name The name to select
     * @return SoftwareType The SoftwareType selected
     * @throws ServiceException
     * @throws \AbstractRepo\Exceptions\RepositoryException If not found
     */
    public function findByName(string $name): SoftwareType
    {
        $softwareType = $this->softwareTypeRepository->findFirst(new FetchParams(
            conditions: "name = :name",
            bind: [
                "name" => $name
            ]
        ));
        if (is_null($softwareType)) {
            throw new ServiceException("Software Type not found");
        }

        return $softwareType;
    }

    /**
     * Select by key
     * @param string $key The key to search
     * @return array The SoftwareTypes selected
     * @throws \AbstractRepo\Exceptions\RepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->softwareTypeRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the softtype
     */
    public function find(): array
    {
        return $this->softwareTypeRepository->find();
    }

    /**
     * Update SoftwareType
     * @param SoftwareType $s The SoftwareType to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(SoftwareType $s): void
    {
        $softT = $this->softwareTypeRepository->findById($s->id);
        if (is_null($softT)) {
            throw new ServiceException("Software Type not found!");
        }

        $this->softwareTypeRepository->update($s);
    }

    /**
     * Delete SoftwareType
     * @param int $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void
    {
        $s = $this->softwareTypeRepository->findById($id);
        if (is_null($s)) {
            throw new ServiceException("Software Type not found!");
        }

        $this->softwareTypeRepository->delete($id);
    }
}
