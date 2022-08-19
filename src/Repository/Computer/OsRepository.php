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

        /**
         * Insert a os
         * @param Os $os    The os to insert
         * @return Os       The os inserted
         * @throws RepositoryException  If the insert fails
         */
        public function insert(Os $os):Os{

            $query = 
            "INSERT INTO os 
            (Name) VALUES 
            (:Name);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("Name",$os->Name,PDO::PARAM_STR);

            try{             
                $stmt->execute();
                $os->OsID = intval($this->pdo->lastInsertId());
                return $os;
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the os with name: {".$os->Name."}");
            }            
        }

        /**
         * Select os by id
         * @param int $OsID     The os id to select
         * @param ?bool $showErased
         * @return ?Os  The os selected, null if not found
         */
        public function selectById(int $OsID,?bool $showErased = false): ?Os
        {            
            $query = "SELECT * FROM os WHERE OsID = :OsID";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("OsID",$OsID,PDO::PARAM_INT);
            $stmt->execute();
            $os = $stmt->fetch(PDO::FETCH_ASSOC);
            if($os){
                return ORM::getNewInstance(Os::class,$os);
            }
            return null;
        }
        
        /**
         * Select os by name
         * @param string $Name  The os name to select
         * @param ?bool $showErased 
         * @return ?Os  The selected os, null if not found
         */
        public function selectByName(string $Name,?bool $showErased = false): ?Os{
            $query = "SELECT * FROM os WHERE Name = :Name";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("Name",$Name,PDO::PARAM_STR);
            $stmt->execute();
            $os = $stmt->fetch(PDO::FETCH_ASSOC);
            if($os){
                return ORM::getNewInstance(Os::class,$os);
            }
            return null;
        }
        
        /**
         * Select all os
         * @param ?bool $showErased
         * @return ?array   The list of os, null if no result
         */
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM os";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $arr_os = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $arr_os;
        }
        
        /**
         * Update a os
         * @param Os $os    The os to update
         * @return Os       The os updated
         * @throws RepositoryException  If the update fails
         */
        public function update(Os $os): Os
        {            
            $query = 
            "UPDATE os 
            SET Name = :Name            
            WHERE OsID = :OsID;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("Name",$os->Name,PDO::PARAM_STR);
            $stmt->bindParam("OsID",$os->OsID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
                return $os;
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the os  with id: {".$os->OsID."}");
            }
        }        
        
        /**
         * Delete an os
         * @param int $OsID     The os id to delete
         * @throws RepositoryException  If the delete fails
         */
        public function delete(int $OsID): void
        {
            $query = 
            "UPDATE os          
            SET Erased = NOW()
            WHERE OsID = :OsID;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("OsID",$OsID,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the os  with id: {".$OsID."}");
            }
        }
        
    }
?>