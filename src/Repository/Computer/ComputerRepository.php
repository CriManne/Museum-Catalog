<?php

    declare(strict_types=1);

    namespace App\Repository\Computer;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    
    use App\Model\Computer\Computer;

    use App\Repository\Computer\CpuRepository;
    use App\Repository\Computer\RamRepository;
    use App\Repository\Computer\OsRepository;

    use PDO;
    use PDOException;   

    class ComputerRepository extends GenericRepository{

        public CpuRepository $cpuRepository;
        public RamRepository $ramRepository;
        public OsRepository $osRepository;

        public function __construct(
            PDO $pdo,
            CpuRepository $cpuRepository,
            RamRepository $ramRepository,
            OsRepository $osRepository
            ){
            parent::__construct($pdo);
            $this->cpuRepository = $cpuRepository;
            $this->ramRepository = $ramRepository;
            $this->osRepository = $osRepository;
                            
        }

        /**
         * Insert Computer
         * @param Computer $computer    The computer to insert
         * @return Computer         The computer inserted
         * @throws RepositoryException  If the insert fails         * 
         */
        public function insert(Computer $computer): Computer{

            $queryComputer = 
                "INSERT INTO computer
                (ObjectID,ModelName,Year,HddSize,CpuID,RamID,OsID) VALUES 
                (:ObjectID,:ModelName,:Year,:HddSize,:CpuID,:RamID,:OsID);";

            $queryObject = 
                "INSERT INTO genericobject
                (ObjectID,Note,Url,Tag,Active,Erased)
                VALUES
                (:ObjectID,:Note,:Url,:Tag,:Active,:Erased)";
            try{             
                $this->pdo->beginTransaction();

                $stmt = $this->pdo->prepare($queryObject);
                $stmt->bindValue(':ObjectID', $computer->ObjectID, PDO::PARAM_STR);
                $stmt->bindValue(':Note', $computer->Note, PDO::PARAM_STR);
                $stmt->bindValue(':Url', $computer->Url, PDO::PARAM_STR);
                $stmt->bindValue(':Tag', $computer->Tag, PDO::PARAM_STR);
                $stmt->bindValue(':Active', $computer->Active, PDO::PARAM_STR);
                $stmt->bindValue(':Erased', $computer->Erased, PDO::PARAM_STR);

                $stmt->execute();

                $stmt = $this->pdo->prepare($queryComputer);
                $stmt->bindParam("ObjectID",$computer->ObjectID,PDO::PARAM_STR);
                $stmt->bindParam("ModelName",$computer->ModelName,PDO::PARAM_STR);
                $stmt->bindParam("Year",$computer->Year,PDO::PARAM_INT);
                $stmt->bindParam("HddSize",$computer->HddSize,PDO::PARAM_STR);
                $stmt->bindParam("CpuID",$computer->Cpu->CpuID,PDO::PARAM_INT);
                $stmt->bindParam("RamID",$computer->Ram->RamID,PDO::PARAM_INT);
                $stmt->bindParam("OsID",$computer->Os->OsID,PDO::PARAM_INT);

                $stmt->execute();

                $this->pdo->commit();
                return $computer;
            }catch(PDOException){
                $this->pdo->rollBack();    
                throw new RepositoryException("Error while inserting the computer with id: {".$computer->ObjectID."}");
            }            
        }
        
        /**
         * Select computer by id
         * @param string $ObjectID  The object id to select
         * @param ?bool $showErased
         * @return ?Computer    The computer selected, null if not found
         */
        public function selectById(string $ObjectID,?bool $showErased = false): ?Computer
        {            
            $query = "SELECT * FROM computer b 
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID 
            WHERE g.ObjectID = :ObjectID";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("ObjectID",$ObjectID,PDO::PARAM_STR);
            $stmt->execute();
            $computer = $stmt->fetch(PDO::FETCH_ASSOC);
            if($computer){
                return $this->returnMappedObject($computer);
            }
            return null;
        }
        
        /**
         * Select computer by model name
         * @param string $ModelName     The computer model name to select
         * @param ?bool $showErased
         * @return ?Computer    The computer selected, null if not found
         */
        public function selectByModelName(string $ModelName,?bool $showErased = false): ?Computer{
            $query = "SELECT * FROM computer b
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID 
            WHERE ModelName LIKE Concat('%',:ModelName,'%')";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("ModelName",$ModelName,PDO::PARAM_STR);
            $stmt->execute();
            $computer = $stmt->fetch(PDO::FETCH_ASSOC);
            if($computer){
                return $this->returnMappedObject($computer);
            }
            return null;
        }
        
        /**
         * Select all computers
         * @param ?bool $showErased
         * @return ?array   All computers, null if no result
         */
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM computer b
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $arr_computer = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $arr_computer;
        }
        
        /**
         * Update a computer
         * @param Computer $s   The computer to update
         * @return Computer     The computer updated
         * @throws RepositoryException  If the update fails
         */
        public function update(Computer $b): void
        {   
            $queryComputer = 
            "UPDATE computer
            SET ModelName = :ModelName,
            Year = :Year,
            HddSize = :HddSize,
            CpuID = :CpuID,
            RamID = :RamID,
            OsID = :OsID            
            WHERE ObjectID = :ObjectID";

            $queryObject = 
            "UPDATE genericobject
            SET Note = :Note,
            Url = :Url,
            Tag = :Tag,
            Active = :Active,
            Erased = :Erased
            WHERE ObjectID = :ObjectID";

            try{             
                $this->pdo->beginTransaction();

                $stmt = $this->pdo->prepare($queryComputer);            
                $stmt->bindParam("ModelName",$b->ModelName,PDO::PARAM_STR);                
                $stmt->bindParam("Year",$b->Year,PDO::PARAM_INT);
                $stmt->bindParam("HddSize",$b->HddSize,PDO::PARAM_STR);
                $stmt->bindParam("CpuID",$b->Cpu->CpuID,PDO::PARAM_INT);
                $stmt->bindParam("RamID",$b->Ram->RamID,PDO::PARAM_INT);
                $stmt->bindParam("OsID",$b->Os->OsID,PDO::PARAM_INT);
                $stmt->bindParam("ObjectID",$b->ObjectID,PDO::PARAM_STR);            
                $stmt->execute();

                $stmt = $this->pdo->prepare($queryObject);
                $stmt->bindParam("Note",$b->Note,PDO::PARAM_STR);
                $stmt->bindParam("Url",$b->Url,PDO::PARAM_STR);
                $stmt->bindParam("Tag",$b->Tag,PDO::PARAM_STR);
                $stmt->bindParam("Active",$b->Active,PDO::PARAM_STR);
                $stmt->bindParam("Erased",$b->Erased,PDO::PARAM_STR);
                $stmt->bindParam("ObjectID",$b->ObjectID,PDO::PARAM_STR);
                $stmt->execute();

                $this->pdo->commit();
            }catch(PDOException $e){
                $this->pdo->rollBack();
                throw new RepositoryException("Error while updating the computer with id: {".$b->ObjectID."}");
            }
        }        
        
        /**
         * Delete a computer
         * @param string $ObjectID  The object id to delete
         * @throws RepositoryException  If the delete fails
         */
        public function delete(string $ObjectID): void
        {
            $query = 
            "UPDATE genericobject
            SET Erased = NOW()
            WHERE ObjectID = :ObjectID;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("ObjectID",$ObjectID,PDO::PARAM_STR);
            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while deleting the computer with id: {".$ObjectID."}");
            }
        }
        
        /**
         * Return a new instance of Computer from an array
         * @param array $rawComputer    The raw computer object
         * @return Computer The new instance of computer with the fk filled with the result of selects
         */
        function returnMappedObject(array $rawComputer): Computer{
            return new Computer(
                $rawComputer["ObjectID"],
                $rawComputer["Note"],
                $rawComputer["Url"],
                $rawComputer["Tag"],
                strval($rawComputer["Active"]),
                $rawComputer["Erased"],
                $rawComputer["ModelName"],
                $rawComputer["Year"],
                $rawComputer["HddSize"],
                $this->cpuRepository->selectById($rawComputer["CpuID"]),
                $this->ramRepository->selectById($rawComputer["RamID"]),
                $this->osRepository->selectById($rawComputer["OsID"])
            );            
        }
    }
?>