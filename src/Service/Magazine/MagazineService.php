<?php

declare(strict_types=1);

namespace App\Service\Magazine;

use App\Exception\ServiceException;
use App\Model\Magazine\Magazine;
use App\Repository\Magazine\MagazineRepository;

class MagazineService {

    public MagazineRepository $magazineRepository;

    public function __construct(MagazineRepository $magazineRepository) {
        $this->magazineRepository = $magazineRepository;
    }

    /**
     * Insert magazine
     * @param Magazine $m The magazine to insert
     * @return Magazine The magazine inserted
     * @throws ServiceException If the Title is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(Magazine $m): Magazine {
        if ($this->magazineRepository->selectByTitle($m->Title) != null)
            throw new ServiceException("Magazine already used!");

        return $this->magazineRepository->insert($m);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Magazine The magazine selected
     * @throws ServiceException If not found
     */
    public function selectById(string $id): Magazine {
        $magazine = $this->magazineRepository->selectById($id);
        if ($magazine == null) throw new ServiceException("Magazine not found");

        return $magazine;
    }

    /**
     * Select by Title
     * @param string $Title The Title to select
     * @return Magazine The magazine selected
     * @throws ServiceException If not found
     */
    public function selectByTitle(string $Title): Magazine {
        $magazine = $this->magazineRepository->selectByTitle($Title);
        if ($magazine == null) throw new ServiceException("Magazine not found");

        return $magazine;
    }

    /**
     * Update a Magazine
     * @param Magazine $m The Magazine to update
     * @return Magazine The magazine updated
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Magazine $m): Magazine {
        if ($this->magazineRepository->selectById($m->ObjectID) == null)
            throw new ServiceException("Magazine not found!");

        return $this->magazineRepository->update($m);
    }

    /**
     * Delete a Magazine
     * @param string $id The id to delete
     * @return Magazine The magazine deleted
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): Magazine {
        $m = $this->magazineRepository->selectById($id);
        if ($m == null)
            throw new ServiceException("Magazine not found!");

        $this->magazineRepository->delete($id);
        return $m;
    }
}
