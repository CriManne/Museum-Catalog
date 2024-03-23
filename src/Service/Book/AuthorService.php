<?php

declare(strict_types=1);

namespace App\Service\Book;

use App\Exception\ServiceException;
use App\Model\Book\Author;
use App\Repository\Book\AuthorRepository;

class AuthorService {

    public AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository) {
        $this->authorRepository = $authorRepository;
    }

    /**
     * Insert an author
     * @param Author $a The author to insert
     * @throws RepositoryException If the insert fails         * 
     */
    public function insert(Author $a): void {
        $this->authorRepository->insert($a);
    }

    /**
     * Select author by id
     * @param int $id   The id to select
     * @return Author   The author selected
     * @throws ServiceException If not found
     */
    public function selectById(int $id): Author {
        $author = $this->authorRepository->selectById($id);
        if (is_null($author)) {
            throw new ServiceException("Author not found");
        }

        return $author;
    }

    /**
     * Select author by key
     * @param string $key  The key to search
     * @return array The authors selected
     */
    public function selectByKey(string $key): array {
        return $this->authorRepository->selectByKey($key);
    }

    /**
     * Select all
     * @return array All the authors
     */
    public function selectAll(): array {
        return $this->authorRepository->selectAll();
    }

    /**
     * Update author
     * @param Author $a The author to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Author $a): void {
        $author = $this->authorRepository->selectById($a->id);
        if (is_null($author)) {
            throw new ServiceException("Author not found!");
        }

        $this->authorRepository->update($a);
    }

    /**
     * Delete author
     * @param int $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void {
        $a = $this->authorRepository->selectById($id);
        if (!$a)
            throw new ServiceException("Author not found!");

        $this->authorRepository->delete($id);
    }
}
