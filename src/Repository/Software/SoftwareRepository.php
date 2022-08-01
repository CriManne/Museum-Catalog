<?php

    declare(strict_types=1);

    namespace App\Repository\Software;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Software\Software;
    use PDO;
    use PDOException;   

    class SoftwareRepository extends GenericRepository{

        //INSERT
        public function insert(Software $software):void{

            try{             
                $this->pdo->beginTransaction();

                $query = 
                "INSERT INTO genericobject
                (ObjectID,Note,Url,Tag,Active,Erased)
                VALUES
                (:ObjectID,:Note,:Url,:Tag,:Active,:Erased)";

                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(':ObjectID', $software->ObjectID, PDO::PARAM_STR);
                $stmt->bindValue(':Note', $software->Note, PDO::PARAM_STR);
                $stmt->bindValue(':Url', $software->Url, PDO::PARAM_STR);
                $stmt->bindValue(':Tag', $software->Tag, PDO::PARAM_STR);
                $stmt->bindValue(':Active', $software->Active, PDO::PARAM_STR);
                $stmt->bindValue(':Erased', $software->Erased, PDO::PARAM_STR);
                $stmt->execute();

                $query = 
                "INSERT INTO software
                (ObjectID,Title,OsID,SoftwareTypeID,SupportTypeID) VALUES 
                (:objID,:title,:osID,:softID,:suppID);";

                $stmt = $this->pdo->prepare($query);            
                $stmt->bindParam("objID",$software->ObjectID,PDO::PARAM_STR);
                $stmt->bindParam("title",$software->Title,PDO::PARAM_STR);
                $stmt->bindParam("osID",$software->os->ID,PDO::PARAM_INT);
                $stmt->bindParam("softID",$software->SoftwareType->ID,PDO::PARAM_INT);
                $stmt->bindParam("suppID",$software->SupportType->ID,PDO::PARAM_INT);

                $stmt->execute();
                
                $this->pdo->commit();

            }catch(PDOException $e){
                $this->pdo->rollBack();    
                throw new RepositoryException("Error while inserting the software with id: {".$software->ObjectID."}");
            }            
        }
        /*
        //SELECT
        public function selectById(string $id,?bool $showErased = false): ?Software
        {            
            $query = "SELECT * FROM software WHERE ObjectID = :id";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("id",$id,PDO::PARAM_STR);
            $stmt->execute();
            $software = $stmt->fetch();
            if($software){
                return new Software(
                    $software["SoftwareID"],
                    $software["Name"]
                );
            }
            return null;
        }
        
        public function selectByName(string $name,?bool $showErased = false): ?Software{
            $query = "SELECT * FROM softwaretype WHERE Name = :name";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$name,PDO::PARAM_STR);
            $stmt->execute();
            $software = $stmt->fetch();
            if($software){
                return new Software(
                    $software["SoftwareID"],
                    $software["Name"]
                );
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
        public function update(Software $s): void
        {            
            $query = 
            "UPDATE softwaretype 
            SET Name = :name            
            WHERE SoftwareID = :id;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("name",$s->Name,PDO::PARAM_STR);
            $stmt->bindParam("id",$s->ID,PDO::PARAM_INT);            
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the software type with id: {".$s->ID."}");
            }
        }        
        
        //DELETE
        public function delete(int $id): void
        {
            $query = 
            "UPDATE softwaretype          
            SET Erased = NOW()
            WHERE SoftwareID = :id;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the software type with id: {".$id."}");
            }
        }
        */
    }
?>