<?php

    declare(strict_types=1);

    namespace App\Repository\Software;

    use App\Repository\GenericRepository;
    use App\Exception\RepositoryException;
    use App\Model\Software\Software;
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

        /**
         * Insert software
         * @param Software $software    The software to insert
         * @return Software         The software inserted
         * @throws RepositoryException  If the insert fails         * 
         */
        public function insert(Software $software):Software{

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
                return $software;
            }catch(PDOException){
                $this->pdo->rollBack();    
                throw new RepositoryException("Error while inserting the software with id: {".$software->ObjectID."}");
            }            
        }
        
        /**
         * Select software by id
         * @param string $ObjectID  The object id to select
         * @param ?bool $showErased
         * @return ?Software    The software selected, null if not found
         */
        public function selectById(string $ObjectID,?bool $showErased = false): ?Software
        {            
            $query = "SELECT * FROM software 
            INNER JOIN genericobject g ON g.ObjectID = software.ObjectID 
            WHERE g.ObjectID = :ObjectID";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("ObjectID",$ObjectID,PDO::PARAM_STR);
            $stmt->execute();
            $software = $stmt->fetch(PDO::FETCH_ASSOC);
            if($software){
                return $this->returnMappedObject($software);
            }
            return null;
        }
        
        /**
         * Select software by title
         * @param string $Title     The software title to select
         * @param ?bool $showErased
         * @return ?Software    The software selected, null if not found
         */
        public function selectByTitle(string $Title,?bool $showErased = false): ?Software{
            $query = "SELECT * FROM software 
            INNER JOIN genericobject g ON g.ObjectID = software.ObjectID 
            WHERE Title = :Title";
            
            if(isset($showErased)){
                $query .= " AND Erased ".($showErased ? "IS NOT NULL;" : "IS NULL;");
            }    
            
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("Title",$Title,PDO::PARAM_STR);
            $stmt->execute();
            $software = $stmt->fetch(PDO::FETCH_ASSOC);
            if($software){
                return $this->returnMappedObject($software);
            }
            return null;
        }
        
        /**
         * Select all software
         * @param ?bool $showErased
         * @return ?array   All software, null if no results
         */
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
        
        /**
         * Update a software
         * @param Software $s   The software to update
         * @return Software     The software updated
         * @throws RepositoryException  If the update fails
         */
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
        
        /**
         * Delete a software
         * @param string $ObjectID  The object id to delete
         * @throws RepositoryException  If the delete fails
         */
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
        
        /**
         * Return a new instance of Software from an array
         * @param array $rawSoftware    The raw software object
         * @return Software The new instance of software with the fk filled with the result of selects
         */
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