<?php
    declare(strict_types=1);

    namespace App\Service\Book;

    use App\Exception\ServiceException;
    use App\Model\Book\BookAuthor;
    use App\Repository\Book\BookAuthorRepository;

    class BookAuthorService{

        public BookAuthorRepository $bookAuthorRepository;

        public function __construct(BookAuthorRepository $bookAuthorRepository)
        {
            $this->bookAuthorRepository = $bookAuthorRepository;
        }

        /**
         * Insert an bookAuthor
         * @param BookAuthor $a The bookAuthor to insert
         * @return BookAuthor The bookAuthor inserted
         * @throws RepositoryException If the insert fails         * 
         */
        public function insert(BookAuthor $a):BookAuthor{
            return $this->bookAuthorRepository->insert($a);
        }

        /**
         * Select bookAuthor by ids
         * @param string $BookID The book id
         * @param int $AuthorID   The author id
         * @return BookAuthor   The bookAuthor selected
         * @throws ServiceException If not found
         */
        public function selectById(string $BookID,int $AuthorID): BookAuthor{
            $bookAuthor = $this->bookAuthorRepository->selectById($BookID,$AuthorID); 
            if($bookAuthor == null) throw new ServiceException("BookAuthor not found");

            return $bookAuthor;
        }

        /**
         * Select bookAuthor by BookID
         * @param string $BookID  The book id
         * @return BookAuthor The bookAuthor selected
         * @throws ServiceException If not found
         */
        public function selectByBookID(string $BookID): BookAuthor{
            $bookAuthor = $this->bookAuthorRepository->selectByBookID($BookID);
            if($bookAuthor == null) throw new ServiceException("Book Author not found");

            return $bookAuthor;
        }       

        /**
         * Delete bookAuthor
         * @param string $BookID The book id
         * @param int $AuthorID   The author id
         * @return BookAuthor The bookAuthor deleted
         * @throws ServiceException If not found
         * @throws RepositoryException If the delete fails
         */
        public function delete(string $BookID,int $AuthorID): BookAuthor{
            $a = $this->bookAuthorRepository->selectById($BookID,$AuthorID);
            if($a == null)
                throw new ServiceException("Author not found!");

            $this->bookAuthorRepository->deleteById($BookID,$AuthorID);
            return $a;
        }
    }

?>