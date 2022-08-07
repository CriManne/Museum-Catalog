<?php

    declare(strict_types=1);

    namespace App\Repository\Computer;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Computer\Os;
    use PDO;
    use PDOException;  
    use App\Util\ORM; 

    class OsRepository extends GenericRepository{

        //INSERT
        public function insert(Os $os):void{

            $query = 
            "INSERT INTO os 
            (Name) VALUES 
            (:name);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$os->Name,PDO::PARAM_STR);

            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the os with name: {".$os->Name."}");
            }            
        }
        //SELECT
        public function selectById(int $id,?bool $showErased = false): ?Os
        {            
            $query = "SELECT * FROM os WHERE OsID = :id";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("id",$id,PDO::PARAM_STR);
            $stmt->execute();
            $os = $stmt->fetch(PDO::FETCH_ASSOC);
            if($os){
                return ORM::getNewInstance(Os::class,$os);
            }
            return null;
        }
        
        public function selectByName(string $name,?bool $showErased = false): ?Os{
            $query = "SELECT * FROM os WHERE Name = :name";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $os = $stmt->fetch(PDO::FETCH_ASSOC);
            if($os){
                return ORM::getNewInstance(Os::class,$os);
            }
            return null;
        }
        
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM os";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $arr_os = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $arr_os;
        }
        
        //UPDATE
        public function update(Os $s): void
        {            
            $query = 
            "UPDATE os 
            SET Name = :name            
            WHERE OsID = :id;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$s->Name,PDO::PARAM_STR);
            $stmt->bindParam("id",$s->OsID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the os  with id: {".$s->OsID."}");
            }
        }        
        
        //DELETE
        public function delete(int $id): void
        {
            $query = 
            "UPDATE os          
            SET Erased = NOW()
            WHERE OsID = :id;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the os  with id: {".$id."}");
            }
        }
        
    }
?>