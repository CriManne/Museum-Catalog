<?php

declare(strict_types=1);

namespace App\Service\Book;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Interfaces\IModel;
use App\Exception\ServiceException;
use App\Models\Book\Publisher;
use App\Repository\Book\PublisherRepository;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use App\Service\IComponentService;

class PublisherService implements IComponentService
{
    public function __construct(
        protected PublisherRepository $publisherRepository
    )
    {
    }

    /**
     * Insert a publisher
     *
     * @param Publisher $p The publisher to save
     *
     * @throws AbstractRepositoryException
     * @throws ServiceException If the publisher name already exists
     */
    public function save(Publisher $p): void
    {
        $publisher = $this->publisherRepository->findFirst(
            new FetchParams(
                conditions: "name = :name",
                bind: [
                    "name" => $p->name
                ]
            )
        );

        if ($publisher) {
            throw new ServiceException("Publisher name already used!");
        }

        $this->publisherRepository->save($p);
    }

    /**
     * Select publisher by id
     *
     * @param int $id The id to select
     *
     * @return Publisher|IModel The publisher selected
     * @throws AbstractRepositoryException
     * @throws ServiceException If the publisher is not found
     */
    public function findById(int $id): Publisher|IModel
    {
        $publisher = $this->publisherRepository->findById($id);
        if (!$publisher) {
            throw new ServiceException("Publisher not found");
        }

        return $publisher;
    }

    /**
     * Select by key
     *
     * @param string $key The key to search
     *
     * @return Publisher[]|IModel[] The publishers selected
     * @throws AbstractRepositoryException
     */
    public function findByQuery(string $key): array
    {
        return $this->publisherRepository->findByQuery($key);
    }

    /**
     * Select all
     * @return Publisher[]|IModel[] All the publisher
     * @throws AbstractRepositoryException
     */
    public function find(): array
    {
        return $this->publisherRepository->find();
    }

    /**
     * Update a publisher
     *
     * @param Publisher $p The publisher to update
     *
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function update(Publisher $p): void
    {
        $publisher = $this->publisherRepository->findById($p->id);
        if (!$publisher) {
            throw new ServiceException("Publisher not found!");
        }

        $this->publisherRepository->update($p);
    }

    /**
     * Delete publisher
     *
     * @param int $id The id to delete
     *
     * @throws AbstractRepositoryException
     * @throws ServiceException If not found
     */
    public function delete(int $id): void
    {
        $p = $this->publisherRepository->findById($id);
        if (!$p) {
            throw new ServiceException("Publisher not found!");
        }

        $this->publisherRepository->delete($id);
    }
}
