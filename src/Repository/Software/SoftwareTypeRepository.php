<?php

    declare(strict_types=1);

    namespace App\Repository\Software;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Software\SoftwareType;
    use PDO;
    use PDOException;   
    use App\Util\ORM;

    class SoftwareTypeRepository extends GenericRepository{

        //INSERT
        public function insert(SoftwareType $softwareType):void{

            $query = 
            "INSERT INTO softwaretype 
            (Name) VALUES 
            (:name);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$softwareType->Name,PDO::PARAM_STR);

            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the software type with name: {".$softwareType->Name."}");
            }            
        }
        //SELECT
        public function selectById(int $id,?bool $showErased = false): ?SoftwareType
        {            
            $query = "SELECT * FROM softwaretype WHERE SoftwareTypeID = :id";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $softwareType = $stmt->fetch(PDO::FETCH_ASSOC);
            if($softwareType){
                return ORM::getNewInstance(SoftwareType::class,$softwareType);
            }
            return null;
        }
        
        public function selectByName(string $name,?bool $showErased = false): ?SoftwareType{
            $query = "SELECT * FROM softwaretype WHERE Name = :name";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $softwareType = $stmt->fetch(PDO::FETCH_ASSOC);
            if($softwareType){
                return ORM::getNewInstance(SoftwareType::class,$softwareType);
            }
            return null;
        }
        
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM softwaretype";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $arr_software = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $arr_software;
        }
        
        //UPDATE
        public function update(SoftwareType $s): void
        {            
            $query = 
            "UPDATE softwaretype 
            SET Name = :name            
            WHERE SoftwareTypeID = :id;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$s->Name,PDO::PARAM_STR);
            $stmt->bindParam("id",$s->SoftwareTypeID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the software type with id: {".$s->SoftwareTypeID."}");
            }
        }        
        
        //DELETE
        public function delete(int $id): void
        {
            $query = 
            "UPDATE softwaretype          
            SET Erased = NOW()
            WHERE SoftwareTypeID = :id;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the software type with id: {".$id."}");
            }
        }
        
    }
?>