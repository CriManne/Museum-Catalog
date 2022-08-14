<?php

    declare(strict_types=1);

    namespace App\Repository\Book;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Book\Publisher;
    use PDO;
    use PDOException;  
    use App\Util\ORM; 

    class PublisherRepository extends GenericRepository{

        //INSERT
        public function insert(Publisher $publisher):void{

            $query = 
            "INSERT INTO publisher 
            (Name) VALUES 
            (:name);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$publisher->Name,PDO::PARAM_STR);

            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the publisher with name: {".$publisher->Name."}");
            }            
        }
        //SELECT
        public function selectById(int $id,?bool $showErased = false): ?Publisher
        {            
            $query = "SELECT * FROM publisher WHERE PublisherID = :id";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $publisher = $stmt->fetch(PDO::FETCH_ASSOC);
            if($publisher){
                return ORM::getNewInstance(Publisher::class,$publisher);
            }
            return null;
        }
        
        public function selectByName(string $name,?bool $showErased = false): ?Publisher{
            $query = "SELECT * FROM publisher WHERE Name = :name";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $publisher = $stmt->fetch(PDO::FETCH_ASSOC);
            if($publisher){
                return ORM::getNewInstance(Publisher::class,$publisher);
            }
            return null;
        }
        
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM publisher";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $arr_cpu = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $arr_cpu;
        }
        
        //UPDATE
        public function update(Publisher $s): void
        {            
            $query = 
            "UPDATE publisher 
            SET Name = :name
            WHERE PublisherID = :id;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$s->Name,PDO::PARAM_STR);
            $stmt->bindParam("id",$s->PublisherID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the publisher with id: {".$s->PublisherID."}");
            }
        }        
        
        //DELETE
        public function delete(int $id): void
        {
            $query = 
            "UPDATE publisher          
            SET Erased = NOW()
            WHERE PublisherID = :id;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the publisher with id: {".$id."}");
            }
        }
        
    }
?>