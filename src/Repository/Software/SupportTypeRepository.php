<?php

    declare(strict_types=1);

    namespace App\Repository\Software;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Software\SupportType;
    use PDO;
    use PDOException;   

    class SupportTypeRepository extends GenericRepository{

        //INSERT
        public function insert(SupportType $supportType):void{

            $query = 
            "INSERT INTO supporttype 
            (Name) VALUES 
            (:name);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$supportType->Name,PDO::PARAM_STR);

            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the support type with name: {".$supportType->Name."}");
            }            
        }
        //SELECT
        public function selectById(int $id,?bool $showErased = false): ?SupportType
        {            
            $query = "SELECT * FROM supporttype WHERE SupportTypeID = :id";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("id",$id,PDO::PARAM_STR);
            $stmt->execute();
            $supportType = $stmt->fetch();
            if($supportType){
                return new SupportType(
                    $supportType["SupportTypeID"],
                    $supportType["Name"]
                );
            }
            return null;
        }
        
        public function selectByName(string $name,?bool $showErased = false): ?SupportType{
            $query = "SELECT * FROM supporttype WHERE Name = :name";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $supportType = $stmt->fetch();
            if($supportType){
                return new SupportType(
                    $supportType["SupportTypeID"],
                    $supportType["Name"]
                );
            }
            return null;
        }
        
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM supporttype";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $supports = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $supports;
        }
        
        //UPDATE
        public function update(SupportType $s): void
        {            
            $query = 
            "UPDATE supporttype 
            SET Name = :name            
            WHERE SupportTypeID = :id;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$s->Name,PDO::PARAM_STR);
            $stmt->bindParam("id",$s->ID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the support type with id: {".$s->ID."}");
            }
        }        
        
        //DELETE
        public function delete(int $id): void
        {
            $query = 
            "UPDATE supporttype          
            SET Erased = NOW()
            WHERE SupportTypeID = :id;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the support type with id: {".$id."}");
            }
        }
        
    }
?>