<?php

declare(strict_types=1);

namespace App\Service\Software;

use App\Exception\ServiceException;
use App\Model\Software\Software;
use App\Repository\Software\SoftwareRepository;

class SoftwareService {

    public SoftwareRepository $softwareRepository;

    public function __construct(SoftwareRepository $softwareRepository) {
        $this->softwareRepository = $softwareRepository;
    }

    /**
     * Insert software
     * @param Software $s The software to insert
     * @return Software The software inserted
     * @throws ServiceException If the title is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(Software $s): Software {
        $software = $this->softwareRepository->selectByTitle($s->Title);
        if ($software)
            throw new ServiceException("Software title already used!");

        return $this->softwareRepository->insert($s);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Software The software selected
     * @throws ServiceException If not found
     */
    public function selectById(string $id): Software {
        $software = $this->softwareRepository->selectById($id);
        if ($software == null) throw new ServiceException("Software not found");

        return $software;
    }

    /**
     * Select by title
     * @param string $title The title to select
     * @return Software The software selected
     * @throws ServiceException If not found
     */
    public function selectByTitle(string $title): Software {
        $software = $this->softwareRepository->selectByTitle($title);
        if ($software == null) throw new ServiceException("Software not found");

        return $software;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array Software(s) selected, empty array if no result
     */
    public function selectByKey(string $key):array {
        return $this->softwareRepository->selectByKey($key);
    }

    /**
     * Select all
     * @return array All the Software(s)
     */
    public function selectAll():array {
        return $this->softwareRepository->selectall();
    }

    /**
     * Update a Software
     * @param Software $s The Software to update
     * @return Software The software updated
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Software $s): Software {
        if ($this->softwareRepository->selectById($s->ObjectID) == null)
            throw new ServiceException("Software not found!");

        return $this->softwareRepository->update($s);
    }

    /**
     * Delete a Software
     * @param string $id The id to delete
     * @return Software The software deleted
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): Software {
        $s = $this->softwareRepository->selectById($id);
        if ($s == null)
            throw new ServiceException("Software not found!");

        $this->softwareRepository->delete($id);
        return $s;
    }
}
