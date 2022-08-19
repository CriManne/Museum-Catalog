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

        /**
         * Insert a ram
         * @param Ram $ram  The ram to insert
         * @return Ram      The ram inserted
         * @throws RepositoryException  If the insert fails
         */
        public function insert(Ram $ram):Ram{

            $query = 
            "INSERT INTO ram 
            (ModelName,Size) VALUES 
            (:name,:size);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$ram->ModelName,PDO::PARAM_STR);
            $stmt->bindParam("size",$ram->Size,PDO::PARAM_STR);

            try{             
                $stmt->execute();
                $ram->RamID = intval($this->pdo->lastInsertId());
                return $ram;                
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the ram with name: {".$ram->ModelName."}");
            }            
        }

        /**
         * Select a ram by id
         * @param int $RamID    The ram id to select
         * @param ?bool $showErased
         * @return ?Ram     The selected ram, null if not found
         */
        public function selectById(int $RamID,?bool $showErased = false): ?Ram
        {            
            $query = "SELECT * FROM ram WHERE RamID = :RamID";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("RamID",$RamID,PDO::PARAM_INT);
            $stmt->execute();
            $ram = $stmt->fetch(PDO::FETCH_ASSOC);
            if($ram){
                return ORM::getNewInstance(Ram::class,$ram);
            }
            return null;
        }
        
        /**
         * Select ram by name
         * @param string $ModelName     The ram name to select
         * @param ?bool $showErased
         * @return ?Ram     The ram selected,null if not found
         */
        public function selectByName(string $ModelName,?bool $showErased = false): ?Ram{
            $query = "SELECT * FROM ram WHERE ModelName = :ModelName";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("ModelName",$ModelName,PDO::PARAM_STR);
            $stmt->execute();
            $ram = $stmt->fetch(PDO::FETCH_ASSOC);
            if($ram){
                return ORM::getNewInstance(Ram::class,$ram);
            }
            return null;
        }
        
        /**
         * Select all rams
         * @param ?bool $showErased
         * @return ?array   The rams selected, null if no result
         */
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM ram";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $arr_ram = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $arr_ram;
        }
        
        /**
         * Update a ram
         * @param Ram $ram  The ram to update
         * @return Ram      The ram updated
         * @throws RepositoryException  If the update fails
         */
        public function update(Ram $ram): void
        {            
            $query = 
            "UPDATE ram 
            SET ModelName = :ModelName,
            Size = :Size
            WHERE RamID = :RamID;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("ModelName",$ram->ModelName,PDO::PARAM_STR);
            $stmt->bindParam("Size",$ram->Size,PDO::PARAM_STR);
            $stmt->bindParam("RamID",$ram->RamID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the ram with id: {".$ram->RamID."}");
            }
        }        
        
        /**
         * Delete a ram
         * @param int $RamID    The ram id to delete
         * @throws RepositoryException  If the delete fails
         */
        public function delete(int $RamID): void
        {
            $query = 
            "UPDATE ram          
            SET Erased = NOW()
            WHERE RamID = :RamID;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("RamID",$RamID,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the ram with id: {".$RamID."}");
            }
        }
        
    }
?>