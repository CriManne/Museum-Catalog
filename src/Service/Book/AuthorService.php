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
     * @return Author The author inserted
     * @throws RepositoryException If the insert fails         * 
     */
    public function insert(Author $a): Author {
        return $this->authorRepository->insert($a);
    }

    /**
     * Select author by id
     * @param int $id   The id to select
     * @return Author   The author selected
     * @throws ServiceException If not found
     */
    public function selectById(int $id): Author {
        $author = $this->authorRepository->selectById($id);
        if ($author == null) throw new ServiceException("Author not found");

        return $author;
    }

    /**
     * Select author by fullname
     * @param string $fullname  The fullname of the author
     * @return Author The author selected
     * @throws ServiceException If not found
     */
    public function selectByFullName(string $fullname): Author {
        $author = $this->authorRepository->selectByFullName($fullname);
        if ($author == null) throw new ServiceException("Author not found");

        return $author;
    }

    /**
     * Update author
     * @param Author $a The author to update
     * @return Author The author updated
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Author $a): Author {
        if ($this->authorRepository->selectById($a->AuthorID) == null)
            throw new ServiceException("Author not found!");

        return $this->authorRepository->update($a);
    }

    /**
     * Delete author
     * @param int $id The id to delete
     * @return Author The author deleted
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): Author {
        $a = $this->authorRepository->selectById($id);
        if ($a == null)
            throw new ServiceException("Author not found!");

        $this->authorRepository->delete($id);
        return $a;
    }
}
