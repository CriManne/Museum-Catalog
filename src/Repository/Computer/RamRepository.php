<?php

    declare(strict_types=1);

    namespace App\Repository\Computer;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Computer\Ram;
    use PDO;
    use PDOException;  
    use App\Util\ORM; 

    class RamRepository extends GenericRepository{

        //INSERT
        public function insert(Ram $ram):void{

            $query = 
            "INSERT INTO ram 
            (ModelName,Size) VALUES 
            (:name,:size);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$ram->ModelName,PDO::PARAM_STR);
            $stmt->bindParam("size",$ram->Size,PDO::PARAM_STR);

            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the ram with name: {".$ram->ModelName."}");
            }            
        }
        //SELECT
        public function selectById(int $id,?bool $showErased = false): ?Ram
        {            
            $query = "SELECT * FROM ram WHERE RamID = :id";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("id",$id,PDO::PARAM_STR);
            $stmt->execute();
            $ram = $stmt->fetch(PDO::FETCH_ASSOC);
            if($ram){
                return ORM::getNewInstance(Ram::class,$ram);
            }
            return null;
        }
        
        public function selectByName(string $name,?bool $showErased = false): ?Ram{
            $query = "SELECT * FROM ram WHERE ModelName = :name";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $ram = $stmt->fetch(PDO::FETCH_ASSOC);
            if($ram){
                return ORM::getNewInstance(Ram::class,$ram);
            }
            return null;
        }
        
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM ram";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $arr_ram = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $arr_ram;
        }
        
        //UPDATE
        public function update(Ram $s): void
        {            
            $query = 
            "UPDATE ram 
            SET ModelName = :name,
            Size = :size
            WHERE RamID = :id;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$s->ModelName,PDO::PARAM_STR);
            $stmt->bindParam("size",$s->Size,PDO::PARAM_STR);
            $stmt->bindParam("id",$s->RamID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the ram with id: {".$s->RamID."}");
            }
        }        
        
        //DELETE
        public function delete(int $id): void
        {
            $query = 
            "UPDATE ram          
            SET Erased = NOW()
            WHERE RamID = :id;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the ram with id: {".$id."}");
            }
        }
        
    }
?>