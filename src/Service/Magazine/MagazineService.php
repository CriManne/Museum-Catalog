<?php

declare(strict_types=1);

namespace App\Service\Magazine;

use App\Exception\ServiceException;
use App\Model\Magazine\Magazine;
use App\Repository\Magazine\MagazineRepository;
use App\Exception\RepositoryException;

class MagazineService {

    public MagazineRepository $magazineRepository;

    public function __construct(MagazineRepository $magazineRepository) {
        $this->magazineRepository = $magazineRepository;
    }

    /**
     * Insert magazine
     * @param Magazine $m The magazine to save
     * @throws ServiceException If the title is already used
     * @throws RepositoryException If the save fails
     */
    public function save(Magazine $m): void {
        $magazine = $this->magazineRepository->findByTitle($m->title);
        if ($magazine)
            throw new ServiceException("Magazine title already used!");

        $this->magazineRepository->save($m);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Magazine The magazine selected
     * @throws ServiceException If not found
     */
    public function findById(string $id): Magazine {
        $magazine = $this->magazineRepository->findById($id);
        if (is_null($magazine)) {
            throw new ServiceException("Magazine not found");
        }

        return $magazine;
    }

    /**
     * Select by title
     * @param string $title The title to select
     * @return Magazine The magazine selected
     * @throws ServiceException If not found
     */
    public function findByTitle(string $title): Magazine {
        $magazine = $this->magazineRepository->findByTitle($title);
        if (is_null($magazine)) {
            throw new ServiceException("Magazine not found");
        }

        return $magazine;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array Magazines selected, empty array if no result
     */
    public function findByKey(string $key): array {
        return $this->magazineRepository->findByKey($key);
    }

    /**
     * Select all
     * @return array All the magazines
     */
    public function findAll(): array {
        return $this->magazineRepository->findAll();
    }

    /**
     * Update a Magazine
     * @param Magazine $m The Magazine to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Magazine $m): void {
        $mag = $this->magazineRepository->findById($m->objectId);

        if (is_null($mag)) {
            throw new ServiceException("Magazine not found!");
        }

        $this->magazineRepository->update($m);
    }

    /**
     * Delete a Magazine
     * @param string $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void {
        $m = $this->magazineRepository->findById($id);

        if (is_null($m)) {
            throw new ServiceException("Magazine not found!");
        }

        $this->magazineRepository->delete($id);
    }
}
