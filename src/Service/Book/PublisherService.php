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

        public function insert(Publisher $s):void{
            $publisher = $this->publisherRepository->selectById($s->PublisherID);
            if($publisher->Name == $s->Name)
                throw new ServiceException("Publisher name already used!");

            $this->publisherRepository->insert($s);
        }

        public function selectById(int $id): Publisher{
            $publisher = $this->publisherRepository->selectById($id); 
            if($publisher == null) throw new ServiceException("Publisher not found");

            return $publisher;
        }

        public function selectByName(string $name): Publisher{
            $publisher = $this->publisherRepository->selectByName($name);
            if($publisher == null) throw new ServiceException("Publisher not found");

            return $publisher;
        }

        public function update(Publisher $s):void{
            if($this->publisherRepository->selectById($s->PublisherID) == null)
                throw new ServiceException("Publisher not found!");

            $this->publisherRepository->update($s);
        }

        public function delete(int $id): void{
            if($this->publisherRepository->selectById($id) == null)
                throw new ServiceException("Publisher not found!");

            $this->publisherRepository->delete($id);
        }
    }

?>