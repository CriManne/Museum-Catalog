<?php

    declare(strict_types=1);

    namespace App\Repository\Computer;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Computer\Cpu;
    use PDO;
    use PDOException;  
    use App\Util\ORM; 

    class CpuRepository extends GenericRepository{

        //INSERT
        public function insert(Cpu $cpu):void{

            $query = 
            "INSERT INTO cpu 
            (ModelName,Speed) VALUES 
            (:name,:speed);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$cpu->ModelName,PDO::PARAM_STR);
            $stmt->bindParam("speed",$cpu->Speed,PDO::PARAM_STR);

            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the cpu with name: {".$cpu->ModelName."}");
            }            
        }
        //SELECT
        public function selectById(int $id,?bool $showErased = false): ?Cpu
        {            
            $query = "SELECT * FROM cpu WHERE CpuID = :id";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            $stmt->execute();
            $cpu = $stmt->fetch(PDO::FETCH_ASSOC);
            if($cpu){
                return ORM::getNewInstance(Cpu::class,$cpu);
            }
            return null;
        }
        
        public function selectByName(string $name,?bool $showErased = false): ?Cpu{
            $query = "SELECT * FROM cpu WHERE ModelName = :name";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $cpu = $stmt->fetch(PDO::FETCH_ASSOC);
            if($cpu){
                return ORM::getNewInstance(Cpu::class,$cpu);
            }
            return null;
        }
        
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM cpu";
            
            if(isset($showErased)){
                $query .= " WHERE Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->query($query);
            
            $arr_cpu = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            
            
            return $arr_cpu;
        }
        
        //UPDATE
        public function update(Cpu $s): void
        {            
            $query = 
            "UPDATE cpu 
            SET ModelName = :name,
            Speed = :speed
            WHERE CpuID = :id;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$s->ModelName,PDO::PARAM_STR);
            $stmt->bindParam("speed",$s->Speed,PDO::PARAM_STR);
            $stmt->bindParam("id",$s->CpuID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the cpu with id: {".$s->CpuID."}");
            }
        }        
        
        //DELETE
        public function delete(int $id): void
        {
            $query = 
            "UPDATE cpu          
            SET Erased = NOW()
            WHERE CpuID = :id;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the cpu with id: {".$id."}");
            }
        }
        
    }
?>