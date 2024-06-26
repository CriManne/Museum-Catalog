<?php

declare(strict_types=1);

namespace App\Service\Book;

use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\ServiceException;
use App\Models\Book\Author;
use App\Repository\Book\AuthorRepository;
use App\Service\IComponentService;

class AuthorService implements IComponentService
{
    public function __construct(
        protected AuthorRepository $authorRepository
    )
    {
    }

    /**
     * Insert an author
     *
     * @param Author $a The author to save
     *
     * @throws RepositoryException If the save fails
     */
    public function save(Author $a): void
    {
        $this->authorRepository->save($a);
    }

    /**
     * Select author by id
     *
     * @param int $id The id to select
     *
     * @return Author   The author selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findById(int $id): Author
    {
        $author = $this->authorRepository->findById($id);
        if (!$author) {
            throw new ServiceException("Author not found");
        }

        return $author;
    }

    /**
     * Select author by key
     *
     * @param string $key The key to search
     *
     * @return array The authors selected
     * @throws RepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->authorRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the authors
     * @throws RepositoryException
     */
    public function find(): array
    {
        return $this->authorRepository->find();
    }

    /**
     * Update author
     *
     * @param Author $a The author to update
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Author $a): void
    {
        $author = $this->authorRepository->findById($a->id);
        if (!$author) {
            throw new ServiceException("Author not found!");
        }

        $this->authorRepository->update($a);
    }

    /**
     * Delete author
     *
     * @param int $id The id to delete
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void
    {
        $author = $this->authorRepository->findById($id);
        if (!$author) {
            throw new ServiceException("Author not found!");
        }

        $this->authorRepository->delete($id);
    }
}
