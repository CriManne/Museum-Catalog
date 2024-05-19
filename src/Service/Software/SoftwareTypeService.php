<?php

declare(strict_types=1);

namespace App\Service\Software;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\ServiceException;
use App\Models\Software\SoftwareType;
use App\Repository\Software\SoftwareTypeRepository;
use App\Service\IComponentService;

class SoftwareTypeService implements IComponentService
{
    public function __construct(
        protected SoftwareTypeRepository $softwareTypeRepository
    )
    {
    }

    /**
     * Insert SoftwareType
     *
     * @param SoftwareType $s The SoftwareType to save
     *
     * @throws ServiceException If the name is already used
     * @throws RepositoryException If the save fails
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
     *
     * @param int $id The id to select
     *
     * @return SoftwareType The SoftwareType selected
     * @throws ServiceException If not found
     * @throws RepositoryException
     */
    public function findById(int $id): SoftwareType
    {
        $softwareType = $this->softwareTypeRepository->findById($id);

        if (!$softwareType) {
            throw new ServiceException("Software Type not found");
        }

        return $softwareType;
    }

    /**
     * Select by name
     *
     * @param string $name The name to select
     *
     * @return SoftwareType The SoftwareType selected
     * @throws ServiceException
     * @throws RepositoryException If not found
     */
    public function findByName(string $name): SoftwareType
    {
        $softwareType = $this->softwareTypeRepository->findFirst(new FetchParams(
            conditions: "name = :name",
            bind: [
                "name" => $name
            ]
        ));

        if (!$softwareType) {
            throw new ServiceException("Software Type not found");
        }

        return $softwareType;
    }

    /**
     * Select by key
     *
     * @param string $key The key to search
     *
     * @return array The SoftwareTypes selected
     * @throws RepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->softwareTypeRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the software type
     * @throws RepositoryException
     */
    public function find(): array
    {
        return $this->softwareTypeRepository->find();
    }

    /**
     * Update SoftwareType
     *
     * @param SoftwareType $s The SoftwareType to update
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(SoftwareType $s): void
    {
        $softT = $this->softwareTypeRepository->findById($s->id);
        if (!$softT) {
            throw new ServiceException("Software Type not found!");
        }

        $this->softwareTypeRepository->update($s);
    }

    /**
     * Delete SoftwareType
     *
     * @param int $id The id to delete
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void
    {
        $s = $this->softwareTypeRepository->findById($id);
        if (!$s) {
            throw new ServiceException("Software Type not found!");
        }

        $this->softwareTypeRepository->delete($id);
    }
}
