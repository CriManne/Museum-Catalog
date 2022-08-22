<?php
    declare(strict_types=1);

    namespace App\Service\Book;

    use App\Exception\ServiceException;
    use App\Model\Book\Publisher;
    use App\Repository\Book\PublisherRepository;

    class PublisherService{

        public PublisherRepository $publisherRepository;

        public function __construct(PublisherRepository $publisherRepository)
        {
            $this->publisherRepository = $publisherRepository;
        }

        /**
         * Insert a publisher
         * @param Publisher $p  The publisher to insert
         * @return Publisher The publisher inserted
         * @throws ServiceException If the publisher name already exists
         * @throws RepositoryException If the insert fails
         */
        public function insert(Publisher $p):Publisher{
            $publisher = $this->publisherRepository->selectById($p->PublisherID);
            if($publisher->Name == $p->Name)
                throw new ServiceException("Publisher name already used!");

            return $this->publisherRepository->insert($p);
        }

        /**
         * Select publisher by id
         * @param int $id The id to select
         * @return Publisher The publisher selected
         * @throws ServiceException If the publisher is not found
         */
        public function selectById(int $id): Publisher{
            $publisher = $this->publisherRepository->selectById($id); 
            if($publisher == null) throw new ServiceException("Publisher not found");

            return $publisher;
        }

        /**
         * Select by name
         * @param string $name The publisher name to select
         * @return Publisher The publisher selected
         * @throws ServiceException If not found
         */
        public function selectByName(string $name): Publisher{
            $publisher = $this->publisherRepository->selectByName($name);
            if($publisher == null) throw new ServiceException("Publisher not found");

            return $publisher;
        }

        /**
         * Update a publisher
         * @param Publisher $p  The publisher to update
         * @return Publisher The publisher updated
         * @throws ServiceException If not found
         * @throws RepositoryException If the update fails
         */
        public function update(Publisher $p):Publisher{
            if($this->publisherRepository->selectById($p->PublisherID) == null)
                throw new ServiceException("Publisher not found!");

            return $this->publisherRepository->update($p);
        }

        /**
         * Delete publisher
         * @param int $id The id to delete
         * @return Publisher The publisher deleted
         * @throws ServiceException If not found
         * @throws RepositoryException If the delete fails
         */
        public function delete(int $id): Publisher{
            $p = $this->publisherRepository->selectById($id);
            if($p == null)
                throw new ServiceException("Publisher not found!");

            $this->publisherRepository->delete($id);
            return $p;
        }
    }

?>