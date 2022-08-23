<?php
    declare(strict_types=1);

    namespace App\Service\Book;

    use App\Exception\ServiceException;
    use App\Model\Book\Book;
    use App\Repository\Book\BookRepository;

    class BookService{

        public BookRepository $bookRepository;

        public function __construct(BookRepository $bookRepository)
        {
            $this->bookRepository = $bookRepository;
        }

        /**
         * Insert book
         * @param Book $b The book to insert
         * @return Book The book inserted
         * @throws ServiceException If the title is already used
         * @throws RepositoryException If the insert fails
         */
        public function insert(Book $b):Book{
            if($this->bookRepository->selectByTitle($b->Title) != null)
                throw new ServiceException("Book already used!");

            return $this->bookRepository->insert($b);
        }

        /**
         * Select by id
         * @param string $id The id to select
         * @return Book The book selected
         * @throws ServiceException If not found
         */
        public function selectById(string $id): Book{
            $book = $this->bookRepository->selectById($id); 
            if($book == null) throw new ServiceException("Book not found");

            return $book;
        }

        /**
         * Select by title
         * @param string $title The title to select
         * @return Book The book selected
         * @throws ServiceException If not found
         */
        public function selectByTitle(string $title): Book{
            $book = $this->bookRepository->selectByTitle($title);
            if($book == null) throw new ServiceException("Book not found");

            return $book;
        }

        /**
         * Update a Book
         * @param Book $b The Book to update
         * @return Book The book updated
         * @throws ServiceException If not found
         * @throws RepositoryException If the update fails
         */
        public function update(Book $b):Book{
            if($this->bookRepository->selectById($b->ID) == null)
                throw new ServiceException("Book not found!");

            return $this->bookRepository->update($b);
        }

        /**
         * Delete a Book
         * @param string $id The id to delete
         * @return Book The book deleted
         * @throws ServiceException If not found
         * @throws RepositoryException If the delete fails
         */
        public function delete(string $id): Book{
            $b = $this->bookRepository->selectById($id);
            if($b == null)
                throw new ServiceException("Book not found!");

            $this->bookRepository->delete($id);
            return $b;
        }
    }

?>