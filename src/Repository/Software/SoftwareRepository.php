<?php

    declare(strict_types=1);

    namespace App\Repository\Software;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Software\Software;
    use App\Model\Computer\Os;
    use App\Model\Software\SoftwareType;
    use App\Model\Software\SupportType;
    use App\Repository\Computer\OsRepository;
    use App\Repository\Software\SupportTypeRepository;
    use App\Repository\Software\SoftwareTypeRepository;
use PDO;
    use PDOException;   

    class SoftwareRepository extends GenericRepository{

        public SoftwareTypeRepository $softwareTypeRepository;
        public SupportTypeRepository $supportTypeRepository;
        public OsRepository $osRepository;

        public function __construct(
            PDO $pdo,
            SoftwareTypeRepository $softwareTypeRepository,
            SupportTypeRepository $supportTypeRepository,
            OsRepository $osRepository
            ){
            parent::__construct($pdo);
            $this->softwareTypeRepository = $softwareTypeRepository;
            $this->supportTypeRepository = $supportTypeRepository;
            $this->osRepository = $osRepository;
                            
        }

        //INSERT
        public function insert(Software $software):void{

            $querySoftware = 
                "INSERT INTO software
                (ObjectID,Title,OsID,SoftwareTypeID,SupportTypeID) VALUES 
                (:objID,:title,:osID,:softID,:suppID);";

            $queryObject = 
                "INSERT INTO genericobject
                (ObjectID,Note,Url,Tag,Active,Erased)
                VALUES
                (:ObjectID,:Note,:Url,:Tag,:Active,:Erased)";
            try{             
                $this->pdo->beginTransaction();

                $stmt = $this->pdo->prepare($queryObject);
                $stmt->bindValue(':ObjectID', $software->ObjectID, PDO::PARAM_STR);
                $stmt->bindValue(':Note', $software->Note, PDO::PARAM_STR);
                $stmt->bindValue(':Url', $software->Url, PDO::PARAM_STR);
                $stmt->bindValue(':Tag', $software->Tag, PDO::PARAM_STR);
                $stmt->bindValue(':Active', $software->Active, PDO::PARAM_STR);
                $stmt->bindValue(':Erased', $software->Erased, PDO::PARAM_STR);
                $stmt->execute();

                $stmt = $this->pdo->prepare($querySoftware);            
                $stmt->bindParam("objID",$software->ObjectID,PDO::PARAM_STR);
                $stmt->bindParam("title",$software->Title,PDO::PARAM_STR);
                $stmt->bindParam("osID",$software->os->OsID,PDO::PARAM_INT);
                $stmt->bindParam("softID",$software->SoftwareType->SoftwareTypeID,PDO::PARAM_INT);
                $stmt->bindParam("suppID",$software->SupportType->SupportTypeID,PDO::PARAM_INT);

                $stmt->execute();

                $this->pdo->commit();     

            }catch(PDOException){
                $this->pdo->rollBack();    
                throw new RepositoryException("Error while inserting the software with id: {".$software->ObjectID."}");
            }            
        }
        //SELECT
        public function selectById(string $id,?bool $showErased = false): ?Software
        {            
            $query = "SELECT * FROM software 
            INNER JOIN genericobject g ON g.ObjectID = software.ObjectID 
            WHERE g.ObjectID = :id";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("id",$id,PDO::PARAM_STR);
            $stmt->execute();
            $software = $stmt->fetch();
            if($software){
                return $this->returnMappedObject($software);
            }
            return null;
        }
        
        
        public function selectByTitle(string $title,?bool $showErased = false): ?Software{
            $query = "SELECT * FROM software 
            INNER JOIN genericobject g ON g.ObjectID = software.ObjectID 
            WHERE Title = :title";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("title",$title,PDO::PARAM_STR);
            $stmt->execute();
            $software = $stmt->fetch();
            if($software){
                return $this->returnMappedObject($software);
            }
            return null;
        }
        
        public function selectAll(?bool $showErased = false): ?array{
            $query = "SELECT * FROM software
            INNER JOIN genericobject g ON g.ObjectID = software.ObjectID";
            
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
            $querySoftware = 
            "UPDATE software
            SET Title = :title,
            OsID = :osID,
            SoftwareTypeID = :softID,
            SupportTypeID = :suppID
            WHERE ObjectID = :objID";

            $queryObject = 
            "UPDATE genericobject
            SET Note = :note,
            Url = :url,
            Tag = :tag,
            Active = :active,
            Erased = :erased
            WHERE ObjectID = :objID";

            try{             
                $this->pdo->beginTransaction();

                $stmt = $this->pdo->prepare($querySoftware);            
                $stmt->bindParam("title",$s->Title,PDO::PARAM_STR);
                $stmt->bindParam("osID",$s->os->OsID,PDO::PARAM_INT);
                $stmt->bindParam("softID",$s->SoftwareType->SoftwareTypeID,PDO::PARAM_INT);
                $stmt->bindParam("suppID",$s->SupportType->SupportTypeID,PDO::PARAM_INT);
                $stmt->bindParam("objID",$s->ObjectID,PDO::PARAM_INT);            
                $stmt->execute();

                $stmt = $this->pdo->prepare($queryObject);
                $stmt->bindParam("note",$s->Note,PDO::PARAM_STR);
                $stmt->bindParam("url",$s->Url,PDO::PARAM_STR);
                $stmt->bindParam("tag",$s->Tag,PDO::PARAM_STR);
                $stmt->bindParam("active",$s->Active,PDO::PARAM_STR);
                $stmt->bindParam("erased",$s->Erased,PDO::PARAM_STR);
                $stmt->bindParam("objID",$s->ObjectID,PDO::PARAM_INT);
                $stmt->execute();

                $this->pdo->commit();
            }catch(PDOException $e){
                $this->pdo->rollBack();
                throw new RepositoryException("Error while updating the software with id: {".$s->ID."}");
            }
        }        
        
        //DELETE
        public function delete(string $id): void
        {
            $query = 
            "UPDATE genericobject
            SET Erased = NOW()
            WHERE ObjectID = :id;"; 

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("id",$id,PDO::PARAM_INT);
            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while deleting the software with id: {".$id."}");
            }
        }
        

        function returnMappedObject(array $rawsoftware): Software{
            return new Software(
                $rawsoftware["ObjectID"],
                $rawsoftware["Note"],
                $rawsoftware["Url"],
                $rawsoftware["Tag"],
                strval($rawsoftware["Active"]),
                $rawsoftware["Erased"],
                $rawsoftware["Title"],
                $this->osRepository->selectById($rawsoftware["OsID"]),
                $this->softwareTypeRepository->selectById($rawsoftware["SoftwareTypeID"]),
                $this->supportTypeRepository->selectById($rawsoftware["SupportTypeID"])
            );
        }
    }
?>