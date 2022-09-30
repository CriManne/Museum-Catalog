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
     * @throws ServiceException If the Title is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(Magazine $m): void {
        $magazine = $this->magazineRepository->selectByTitle($m->Title);
        if ($magazine)
            throw new ServiceException("Magazine title already used!");

        $this->magazineRepository->insert($m);
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
     * Select by key
     * @param string $key The key given
     * @return array Magazines selected, empty array if no result
     */
    public function selectByKey(string $key):array {
        return $this->magazineRepository->selectByKey($key);
    }

    /**
     * Select all
     * @return array All the magazines
     */
    public function selectAll():array {
        return $this->magazineRepository->selectAll();
    }

    /**
     * Update a Magazine
     * @param Magazine $m The Magazine to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Magazine $m): void {
        if ($this->magazineRepository->selectById($m->ObjectID) == null)
            throw new ServiceException("Magazine not found!");

        $this->magazineRepository->update($m);
    }

    /**
     * Delete a Magazine
     * @param string $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void {
        $m = $this->magazineRepository->selectById($id);
        if ($m == null)
            throw new ServiceException("Magazine not found!");

        $this->magazineRepository->delete($id);
    }
}
