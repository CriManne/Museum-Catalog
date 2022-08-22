<?php

    declare(strict_types=1);

    namespace App\Repository\Book;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Book\BookAuthor;
    use PDO;
    use PDOException;  
    use App\Util\ORM; 

    class BookAuthorRepository extends GenericRepository{

        /**
         * Insert a book author
         * @param BookAuthor $bookAuthor    The book author to insert
         * @return BookAuthor           The book author inserted
         * @throws RepositoryException  If the insert fails
         */
        public function insert(BookAuthor $bookAuthor):BookAuthor{

            $query = 
            "INSERT INTO bookauthor 
            (BookID,AuthorID) VALUES 
            (:BookID,:AuthorID);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("BookID",$bookAuthor->BookID,PDO::PARAM_STR);
            $stmt->bindParam("AuthorID",$bookAuthor->AuthorID,PDO::PARAM_INT);

            try{             
                $stmt->execute();
                return $bookAuthor;
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the book author {".$bookAuthor."}");
            }            
        }
        
        /**
         * Select book author by id
         * @param string $BookID   The book id
         * @param int $AuthorID    The author id
         * @param ?bool $showErased     If true it will show the 'soft' deleted ones, just the present one otherwise, if null both
         * @return ?BookAuthor  The selected book author, null if not found
         */
        public function selectById(string $BookID,int $AuthorID,?bool $showErased = false): ?BookAuthor
        {            
            $query = "SELECT * FROM bookauthor WHERE BookID = :BookID AND AuthorID = :AuthorID";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("BookID",$BookID,PDO::PARAM_STR);
            $stmt->bindParam("AuthorID",$AuthorID,PDO::PARAM_INT);
            $stmt->execute();
            $bookAuthor = $stmt->fetch(PDO::FETCH_ASSOC);
            if($bookAuthor){
                return ORM::getNewInstance(BookAuthor::class,$bookAuthor);
            }

            return null; 
        }

        /**
         * Select book authors by book id
         * @param string $BookID   The book id
         * @param ?bool $showErased     If true it will show the 'soft' deleted ones, just the present one otherwise, if null both
         * @return ?BookAuthor  The selected book author, null if not found
         */
        public function selectByBookId(string $BookID,?bool $showErased = false): ?array
        {            
            $query = "SELECT * FROM bookauthor WHERE BookID = :BookID";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("BookID",$BookID,PDO::PARAM_STR);
            $stmt->execute();
            $bookAuthors = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);
            
            return $bookAuthors;            
        }

        /**
         * Select book authors by author id
         * @param int $AuthorID    The author id
         * @param ?bool $showErased     If true it will show the 'soft' deleted ones, just the present one otherwise, if null both
         * @return ?BookAuthor  The selected book author, null if not found
         */
        public function selectByAuthorId(int $AuthorID,?bool $showErased = false): ?array
        {            
            $query = "SELECT * FROM bookauthor WHERE AuthorID = :AuthorID";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("AuthorID",$AuthorID,PDO::PARAM_INT);
            $stmt->execute();
            $bookAuthors = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);

            return $bookAuthors;            
        }

        /**
         * Select all book authors
         * @param ?bool $showErased     If true it will show the 'soft' deleted ones, just the present one otherwise, if null both
         * @return ?BookAuthor  The selected book author, null if not found
         */
        public function selectAll(?bool $showErased = false): ?array
        {            
            $query = "SELECT * FROM bookauthor";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           
            $stmt = $this->pdo->prepare($query);            
            $stmt->execute();
            $bookAuthors = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);

            return $bookAuthors;            
        }        

        /**
         * Delete by id
         * @param string $BookID The book id
         * @param int $AuthorID The author id
         * @param ?bool $showErased
         * @throws
         */

        

        


        

        
    }
?>