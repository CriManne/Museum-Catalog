<?php

    declare(strict_types=1);

    namespace App\Repository\Book;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Book\Author;
    use PDO;
    use PDOException;  
    use App\Util\ORM; 

    class AuthorRepository extends GenericRepository{

        //INSERT
        public function insert(Author $author):void{

            $query = 
            "INSERT INTO author 
            (firstname,lastname) VALUES 
            (:firstname,:lastname);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("firstname",$author->firstname,PDO::PARAM_STR);
            $stmt->bindParam("lastname",$author->lastname,PDO::PARAM_STR);

            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the author with name: {".$author->firstname."}");
            }            
        }
        //SELECT
        public function selectById(int $id,?bool $showErased = false): ?Author
        {            
            $query = "SELECT * FROM author WHERE AuthorID = :id";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $author = $stmt->fetch(PDO::FETCH_ASSOC);
            if($author){
                return ORM::getNewInstance(Author::class,$author);
            }
            return null;
        }
        
        public function selectByFullName(string $fullname,?bool $showErased = false): ?Author{
            $query = "SELECT * FROM author WHERE Concat(firstname,' ',lastname) = :fullname OR Concat(lastname,' ',firstname) = :fullname";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("fullname",$fullname,PDO::PARAM_STR);
            $stmt->execute();
            $author = $stmt->fetch(PDO::FETCH_ASSOC);
            if($author){
                return ORM::getNewInstance(Author::class,$author);
            }
            return null;
        }
        
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM author";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $arr_cpu = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $arr_cpu;
        }
        
        //UPDATE
        public function update(Author $s): void
        {            
            $query = 
            "UPDATE author 
            SET firstname = :firstname,
            lastname = :lastname
            WHERE AuthorID = :id;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("firstname",$s->firstname,PDO::PARAM_STR);
            $stmt->bindParam("lastname",$s->lastname,PDO::PARAM_STR);
            $stmt->bindParam("id",$s->AuthorID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the author with id: {".$s->AuthorID."}");
            }
        }        
        
        //DELETE
        public function delete(int $id): void
        {
            $query = 
            "UPDATE author          
            SET Erased = NOW()
            WHERE AuthorID = :id;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the author with id: {".$id."}");
            }
        }
        
    }
?>