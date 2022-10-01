<?php

declare(strict_types=1);

namespace App\Service\Book;

use App\Exception\ServiceException;
use App\Model\Book\Publisher;
use App\Repository\Book\PublisherRepository;

class PublisherService {

    public PublisherRepository $publisherRepository;

    public function __construct(PublisherRepository $publisherRepository) {
        $this->publisherRepository = $publisherRepository;
    }

    /**
     * Insert a publisher
     * @param Publisher $p  The publisher to insert
     * @throws ServiceException If the publisher name already exists
     * @throws RepositoryException If the insert fails
     */
    public function insert(Publisher $p): void {
        $publisher = $this->publisherRepository->selectByName($p->Name);
        if ($publisher)
            throw new ServiceException("Publisher name already used!");

        $this->publisherRepository->insert($p);
    }

    /**
     * Select publisher by id
     * @param int $id The id to select
     * @return Publisher The publisher selected
     * @throws ServiceException If the publisher is not found
     */
    public function selectById(int $id): Publisher {
        $publisher = $this->publisherRepository->selectById($id);
        if (is_null($publisher)){
            throw new ServiceException("Publisher not found");
        } 

        return $publisher;
    }

    /**
     * Select by name
     * @param string $name The publisher name to select
     * @return Publisher The publisher selected
     * @throws ServiceException If not found
     */
    public function selectByName(string $name): Publisher {
        $publisher = $this->publisherRepository->selectByName($name);
        if (is_null($publisher)){
            throw new ServiceException("Publisher not found");
        } 

        return $publisher;
    }

    /**
     * Select by key
     * @param string $key The key to search
     * @return array The publishers selected
     */
    public function selectByKey(string $key): array {
        return $this->publisherRepository->selectByKey($key);
    }

    /**
     * Select all
     * @return array All the publisher
     */
    public function selectAll(): array {
        return $this->publisherRepository->selectAll();
    }

    /**
     * Update a publisher
     * @param Publisher $p  The publisher to update
     * @throws ServiceException If not found
     * @throws RepositoryException If the update fails
     */
    public function update(Publisher $p): void {
        $publisher = $this->publisherRepository->selectById($p->PublisherID);
        if (is_null($publisher)){
            throw new ServiceException("Publisher not found!");
        }

        $this->publisherRepository->update($p);
    }

    /**
     * Delete publisher
     * @param int $id The id to delete
     * @throws ServiceException If not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(int $id): void {
        $p = $this->publisherRepository->selectById($id);
        if (is_null($p)){
            throw new ServiceException("Publisher not found!");
        }

        $this->publisherRepository->delete($id);
    }
}
