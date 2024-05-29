<?php

declare(strict_types=1);

namespace App\Service\Book;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException;
use App\Exception\DatabaseException;
use App\Exception\ServiceException;
use App\Models\Book\Book;
use App\Models\Book\BookHasAuthor;
use App\Models\GenericObject;
use App\Models\IArtifact;
use App\Plugins\DB\DB;
use App\Repository\Book\AuthorRepository;
use App\Repository\Book\BookHasAuthorRepository;
use App\Repository\Book\BookRepository;
use App\Repository\Book\PublisherRepository;
use App\Repository\GenericObjectRepository;
use App\Service\IArtifactService;
use Throwable;

class BookService implements IArtifactService
{
    public function __construct(
        protected BookRepository          $bookRepository,
        protected PublisherRepository     $publisherRepository,
        protected GenericObjectRepository $genericObjectRepository,
        protected AuthorRepository        $authorRepository,
        protected BookHasAuthorRepository $bookHasAuthorRepository
    )
    {
    }

    /**
     * Insert book
     *
     * @param Book $b The book to save
     *
     * @throws RepositoryException If the save fails
     * @throws ServiceException If the title is already used
     * @throws DatabaseException
     * @throws Throwable
     */
    public function save(IArtifact $b): void
    {
        $genericObject = $this->genericObjectRepository->findById($b->genericObject->id);

        if ($genericObject) {
            throw new ServiceException("Object ID already used!");
        }

        $book = $this->bookRepository->findFirst(
            new FetchParams(
                conditions: "title = :title",
                bind: [
                    "title" => $b->title
                ]
            )
        );

        if ($book?->title === $b->title) {
            throw new ServiceException("Book title already used!");
        }

        DB::begin();
        try {
            $this->genericObjectRepository->save($b->genericObject);
            $this->bookRepository->save($b);

            foreach ($b->authors AS $author) {
                $this->bookHasAuthorRepository->save(
                    new BookHasAuthor(
                        book: $b,
                        author: $author
                    )
                );
            }

        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
        DB::commit();
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
     * @throws DatabaseException
     * @throws RepositoryException If the update fails
     * @throws ServiceException If not found
     * @throws Throwable
     */
    public function update(IArtifact $b): void
    {
        $book = $this->bookRepository->findById($b->genericObject->id);
        if (!$book) {
            throw new ServiceException("Book not found!");
        }

        DB::begin();
        try {
            $this->genericObjectRepository->update($book->genericObject);
            $this->bookRepository->update($book);

            $bookHasAuthors = $this->bookHasAuthorRepository->find(
                new FetchParams(
                    conditions: "bookId = :bookId",
                    bind: [
                        "bookId" => $book->genericObject->id
                    ]
                )
            );

            foreach ($bookHasAuthors as $bookHasAuthor){
                $this->bookHasAuthorRepository->delete($bookHasAuthor->id);
            }

            foreach ($b->authors AS $author) {
                $this->bookHasAuthorRepository->save(
                    new BookHasAuthor(
                        book: $b,
                        author: $author
                    )
                );
            }

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Delete a Book
     *
     * @param string $id The id to delete
     *
     * @throws DatabaseException
     * @throws RepositoryException If the delete fails
     * @throws ServiceException If not found
     * @throws Throwable
     */
    public function delete(string $id): void
    {
        $b = $this->bookRepository->findById($id);
        if (!$b) {
            throw new ServiceException("Book not found!");
        }

        DB::begin();
        try {
            $this->bookRepository->delete($id);
            $this->genericObjectRepository->delete($id);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
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

        $authors = [];

        foreach ($request['authors'] AS $authorId) {
            $author = $this->authorRepository->findById($authorId);

            if(!$author) {
                throw new ServiceException('Author not found');
            }

            $authors[] = $author;
        }

        return new Book(
            genericObject: $genericObject,
            title: $request["title"],
            publisher: $publisher,
            year: intval($request["year"]),
            authors: $authors,
            isbn: $request["ISBN"],
            pages: intval($request["Pages"])
        );
    }
}
