<?php
    declare(strict_types=1);

    namespace App\Service\Book;

    use App\Exception\ServiceException;
    use App\Model\Book\Author;
    use App\Repository\Book\AuthorRepository;

    class AuthorService{

        public AuthorRepository $authorRepository;

        public function __construct(AuthorRepository $authorRepository)
        {
            $this->osRepository = $authorRepository;
        }

        public function insert(Author $s):void{
            $this->osRepository->insert($s);
        }

        public function selectById(int $id): Author{
            $author = $this->osRepository->selectById($id); 
            if($author == null) throw new ServiceException("Author not found");

            return $author;
        }

        public function selectByFullName(string $fullname): Author{
            $author = $this->osRepository->selectByFullName($fullname);
            if($author == null) throw new ServiceException("Author not found");

            return $author;
        }

        public function update(Author $s):void{
            if($this->osRepository->selectById($s->AuthorID) == null)
                throw new ServiceException("Author not found!");

            $this->osRepository->update($s);
        }

        public function delete(int $id): void{
            if($this->osRepository->selectById($id) == null)
                throw new ServiceException("Author not found!");

            $this->osRepository->delete($id);
        }
    }

?>