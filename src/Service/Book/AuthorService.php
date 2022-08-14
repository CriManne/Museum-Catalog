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
            $this->authorRepository = $authorRepository;
        }

        public function insert(Author $s):void{
            $this->authorRepository->insert($s);
        }

        public function selectById(int $id): Author{
            $author = $this->authorRepository->selectById($id); 
            if($author == null) throw new ServiceException("Author not found");

            return $author;
        }

        public function selectByFullName(string $fullname): Author{
            $author = $this->authorRepository->selectByFullName($fullname);
            if($author == null) throw new ServiceException("Author not found");

            return $author;
        }

        public function update(Author $s):void{
            if($this->authorRepository->selectById($s->AuthorID) == null)
                throw new ServiceException("Author not found!");

            $this->authorRepository->update($s);
        }

        public function delete(int $id): void{
            if($this->authorRepository->selectById($id) == null)
                throw new ServiceException("Author not found!");

            $this->authorRepository->delete($id);
        }
    }

?>