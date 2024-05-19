<?php

declare(strict_types=1);

namespace App\Service\Book;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\ServiceException;
use App\Models\Book\Book;
use App\Models\Book\Publisher;
use App\Models\GenericObject;
use App\Repository\Book\BookRepository;
use App\Repository\Book\PublisherRepository;
use App\Service\IArtifactService;

class BookService implements IArtifactService
{
    public function __construct(
        protected BookRepository      $bookRepository,
        protected PublisherRepository $publisherRepository
    )
    {
    }

    /**
     * Insert book
     *
     * @param Book $b The book to save
     *
     * @throws ServiceException If the title is already used
     * @throws RepositoryException If the save fails
     */
    public function save(Book $b): void
    {
        $book = $this->bookRepository->findFirst(
            new FetchParams(
                conditions: "title = :title OR objectId = :objectId",
                bind: [
                    "title"    => $b->title,
                    "objectId" => $b->genericObject->id
                ]
            )
        );

        if ($book?->title === $b->title) {
            throw new ServiceException("Book title already used!");
        }

        if ($book?->genericObject?->id === $b->genericObject->id) {
            throw new ServiceException("Object ID already used!");
        }

        $this->bookRepository->save($b);
    }

    /**
     * Select by id
     *
     * @param string $id The id to select
     *
     * @return Book The book selected
     * @throws RepositoryException
     * @throws ServiceException If not found
     */
    public function findById(string $id): Book
    {
        $book = $this->bookRepository->findById($id);
        if (!$book) {
            throw new ServiceException("Book not found");
        }

        return $book;
    }

    /**
     * Select by key
     *
     * @param string $key The key given
     *
     * @return array The array of books, empty if no result
     * @throws RepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->bookRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return array All the books
     * @throws RepositoryException
     */
    public function find(): array
    {
        return $this->bookRepository->find();
    }

    /**
     * Update a Book
     *
     * @param Book $b The Book to update
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Book $b): void
    {
        $book = $this->bookRepository->findById($b->genericObject->id);
        if (!$book) {
            throw new ServiceException("Book not found!");
        }

        $this->bookRepository->update($b);
    }

    /**
     * Delete a Book
     *
     * @param string $id The id to delete
     *
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $id): void
    {
        $b = $this->bookRepository->findById($id);
        if (!$b) {
            throw new ServiceException("Book not found!");
        }

        $this->bookRepository->delete($id);
    }

    /**
     * {@inheritDoc}
     * @param array $request
     *
     * @return Book
     * @throws RepositoryException
     * @throws ServiceException
     */
    public function fromRequest(array $request): Book
    {
        $genericObject = new GenericObject(
            $request["objectId"],
            $request["note"] ?? null,
            $request["url"] ?? null,
            $request["tag"] ?? null
        );

        $publisher = $this->publisherRepository->findById($request["publisherId"]);
        if (!$publisher) {
            throw new ServiceException('Publisher not found');
        }

        return new Book(
            genericObject: $genericObject,
            title: $request["title"],
            publisher: $publisher,
            year: $request["year"],
            authors: [],
            isbn: $request["isbn"],
            pages: $request["pages"]
        );
    }
}
