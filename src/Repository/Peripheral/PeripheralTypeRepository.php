<?php

    declare(strict_types=1);

    namespace App\Repository\Peripheral;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Peripheral\PeripheralType;
    use PDO;
    use PDOException;  
    use App\Util\ORM; 

    class PeripheralTypeRepository extends GenericRepository{

        //INSERT
        public function insert(PeripheralType $peripheralType):void{

            $query = 
            "INSERT INTO peripheraltype 
            (Name) VALUES 
            (:name);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$peripheralType->Name,PDO::PARAM_STR);

            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the peripheraltype with name: {".$peripheralType->Name."}");
            }            
        }
        //SELECT
        public function selectById(int $id,?bool $showErased = false): ?PeripheralType
        {            
            $query = "SELECT * FROM peripheraltype WHERE PeripheralTypeID = :id";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $peripheralType = $stmt->fetch(PDO::FETCH_ASSOC);
            if($peripheralType){
                return ORM::getNewInstance(PeripheralType::class,$peripheralType);
            }
            return null;
        }
        
        public function selectByName(string $name,?bool $showErased = false): ?PeripheralType{
            $query = "SELECT * FROM peripheraltype WHERE Name = :name";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $peripheralType = $stmt->fetch(PDO::FETCH_ASSOC);
            if($peripheralType){
                return ORM::getNewInstance(PeripheralType::class,$peripheralType);
            }
            return null;
        }
        
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM peripheraltype";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $arr_os = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $arr_os;
        }
        
        //UPDATE
        public function update(PeripheralType $s): void
        {            
            $query = 
            "UPDATE peripheraltype 
            SET Name = :name            
            WHERE PeripheralTypeID = :id;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$s->Name,PDO::PARAM_STR);
            $stmt->bindParam("id",$s->PeripheralTypeID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the peripheraltype  with id: {".$s->PeripheralTypeID."}");
            }
        }        
        
        //DELETE
        public function delete(int $id): void
        {
            $query = 
            "UPDATE peripheraltype          
            SET Erased = NOW()
            WHERE PeripheralTypeID = :id;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the peripheraltype  with id: {".$id."}");
            }
        }
        
    }
?>