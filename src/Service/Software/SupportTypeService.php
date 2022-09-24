<?php

declare(strict_types=1);

namespace App\Service\Software;

use App\Exception\ServiceException;
use App\Model\Software\SupportType;
use App\Repository\Software\SupportTypeRepository;

class SupportTypeService {

    public SupportTypeRepository $supportTypeRepository;

    public function __construct(SupportTypeRepository $supportTypeRepository) {
        $this->supportTypeRepository = $supportTypeRepository;
    }

    /**
     * Insert SupportType
     * @param SupportType $s The SupportType to insert
     * @return SupportType The SupportType inserted
     * @throws ServiceException If the name is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(SupportType $s): void {
        $sType = $this->supportTypeRepository->selectByName($s->Name);
        if ($sType)
            throw new ServiceException("Support Type name already used!");

        $this->supportTypeRepository->insert($s);
    }

    /**
     * Select by id
     * @param int $id The id to select
     * @return SupportType The SupportType selected
     * @throws ServiceException If not found
     */
    public function selectById(int $id): SupportType {
        $supportType = $this->supportTypeRepository->selectById($id);
        if ($supportType == null) throw new ServiceException("Support Type not found");

        return $supportType;
    }

    /**
     * Select by name
     * @param string $name The name to select
     * @return SupportType The SupportType selected
     * @throws ServiceException If not found
     */
    public function selectByName(string $name): SupportType {
        $supportType = $this->supportTypeRepository->selectByName($name);
        if ($supportType == null) throw new ServiceException("Support Type not found");

        return $supportType;
    }

    /**
     * Select by key
     * @param string $key The key to search
     * @return array The SupportTypes selected
     */
    public function selectByKey(string $key): array {
        return $this->supportTypeRepository->selectByKey($key);
    }

    /**
     * Select all
     * @return array All the supptype
     */
    public function selectAll(): array {
        return $this->supportTypeRepository->selectAll();
    }

    /**
     * Update SupportType
     * @param SupportType $s The SupportType to update
     * @return SupportType The SupportType updated
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(SupportType $s): SupportType {
        if ($this->supportTypeRepository->selectById($s->SupportTypeID) == null)
            throw new ServiceException("Support Type not found!");

        return $this->supportTypeRepository->update($s);
    }

    /**
     * Delete SupportType
     * @param int $id The id to delete
     * @return SupportType The SupportType deleted
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): SupportType {
        $supportType = $this->supportTypeRepository->selectById($id);
        if ($supportType == null)
            throw new ServiceException("Support Type not found!");

        $this->supportTypeRepository->delete($id);
        return $supportType;
    }
}
