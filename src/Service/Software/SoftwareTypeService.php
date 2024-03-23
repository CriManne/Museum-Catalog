<?php

declare(strict_types=1);

namespace App\Service\Software;

use App\Exception\ServiceException;
use App\Model\Software\SoftwareType;
use App\Repository\Software\SoftwareTypeRepository;
use App\Exception\RepositoryException;

class SoftwareTypeService {

    public SoftwareTypeRepository $softwareTypeRepository;

    public function __construct(SoftwareTypeRepository $softwareTypeRepository) {
        $this->softwareTypeRepository = $softwareTypeRepository;
    }

    /**
     * Insert SoftwareType
     * @param SoftwareType $s The SoftwareType to insert
     * @throws ServiceException If the name is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(SoftwareType $s): void {
        $sType = $this->softwareTypeRepository->selectByName($s->name);
        if ($sType)
            throw new ServiceException("Software Type name already used!");

        $this->softwareTypeRepository->insert($s);
    }

    /**
     * Select by id
     * @param int $id The id to select
     * @return SoftwareType The SoftwareType selected
     * @throws ServiceException If not found
     */
    public function selectById(int $id): SoftwareType {
        $softwareType = $this->softwareTypeRepository->selectById($id);
        if (is_null($softwareType)) {
            throw new ServiceException("Software Type not found");
        }

        return $softwareType;
    }

    /**
     * Select by name
     * @param string $name The name to select
     * @return SoftwareType The SoftwareType selected
     * @throws ServiceException If not found
     */
    public function selectByName(string $name): SoftwareType {
        $softwareType = $this->softwareTypeRepository->selectByName($name);
        if (is_null($softwareType)) {
            throw new ServiceException("Software Type not found");
        }

        return $softwareType;
    }

    /**
     * Select by key
     * @param string $key The key to search
     * @return array The SoftwareTypes selected
     */
    public function selectByKey(string $key): array {
        return $this->softwareTypeRepository->selectByKey($key);
    }

    /**
     * Select all
     * @return array All the softtype
     */
    public function selectAll(): array {
        return $this->softwareTypeRepository->selectAll();
    }

    /**
     * Update SoftwareType
     * @param SoftwareType $s The SoftwareType to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(SoftwareType $s): void {
        $softT = $this->softwareTypeRepository->selectById($s->id);
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
    public function delete(int $id): void {
        $s = $this->softwareTypeRepository->selectById($id);
        if (is_null($s)) {
            throw new ServiceException("Software Type not found!");
        }

        $this->softwareTypeRepository->delete($id);
    }
}
