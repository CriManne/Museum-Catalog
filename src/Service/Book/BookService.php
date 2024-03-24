<?php

declare(strict_types=1);

namespace App\Service\Book;

use App\Exception\RepositoryException;
use App\Exception\ServiceException;
use App\Model\Book\Book;
use App\Repository\Book\BookRepository;

class BookService {

    public BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository) {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Insert book
     * @param Book $b The book to save
     * @throws ServiceException If the title is already used
     * @throws RepositoryException If the save fails
     */
    public function save(Book $b): void {
        $book = $this->bookRepository->findByTitle($b->title);
        if ($book)
            throw new ServiceException("Book title already used!");

        $book = $this->bookRepository->findById($b->objectId);
        if ($book)
            throw new ServiceException("Object ID already used!");

        $this->bookRepository->save($b);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Book The book selected
     * @throws ServiceException If not found
     */
    public function findById(string $id): Book {
        $book = $this->bookRepository->findById($id);
        if (is_null($book)) {
            throw new ServiceException("Book not found");
        }

        return $book;
    }

    /**
     * Select by title
     * @param string $title The title to select
     * @return Book The book selected
     * @throws ServiceException If not found
     */
    public function findByTitle(string $title): Book {
        $book = $this->bookRepository->findByTitle($title);
        if (is_null($book)) {
            throw new ServiceException("Book not found");
        }

        return $book;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @return array The array of books, empty if no result
     */
    public function findByQuery(string $key): array {
        return $this->bookRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the books
     */
    public function find(): array {
        return $this->bookRepository->find();
    }

    /**
     * Update a Book
     * @param Book $b The Book to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Book $b): void {
        $book = $this->bookRepository->findById($b->objectId);
        if (is_null($book)) {
            throw new ServiceException("Book not found!");
        }

        $this->bookRepository->update($b);
    }

    /**
     * Delete a Book
     * @param string $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void {
        $b = $this->bookRepository->findById($id);
        if (is_null($b)) {
            throw new ServiceException("Book not found!");
        }

        $this->bookRepository->delete($id);
    }
}
