<?php

declare(strict_types=1);

namespace App\Service\Book;

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
     * @param Book $b The book to insert
     * @throws ServiceException If the title is already used
     * @throws RepositoryException If the insert fails
     */
    public function insert(Book $b): void {
        $book = $this->bookRepository->selectByTitle($b->Title);
        if ($book)
            throw new ServiceException("Book title already used!");

        $book = $this->bookRepository->selectById($b->ObjectID);
        if ($book)
            throw new ServiceException("Object ID already used!");

        $this->bookRepository->insert($b);
    }

    /**
     * Select by id
     * @param string $id The id to select
     * @return Book The book selected
     * @throws ServiceException If not found
     */
    public function selectById(string $id): Book {
        $book = $this->bookRepository->selectById($id);
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
    public function selectByTitle(string $title): Book {
        $book = $this->bookRepository->selectByTitle($title);
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
    public function selectByKey(string $key): array {
        return $this->bookRepository->selectByKey($key);
    }

    /**
     * Select all
     * @return array All the books
     */
    public function selectAll(): array {
        return $this->bookRepository->selectAll();
    }

    /**
     * Update a Book
     * @param Book $b The Book to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Book $b): void {
        $book = $this->bookRepository->selectById($b->ObjectID);
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
        $b = $this->bookRepository->selectById($id);
        if (is_null($b)) {
            throw new ServiceException("Book not found!");
        }

        $this->bookRepository->delete($id);
    }
}
