<?php

declare(strict_types=1);

namespace App\Repository\Magazine;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;

use App\Model\Magazine\Magazine;

use App\Repository\Book\PublisherRepository;

use PDO;
use PDOException;

class MagazineRepository extends GenericRepository {

    public PublisherRepository $publisherRepository;

    public function __construct(
        PDO $pdo,
        PublisherRepository $publisherRepository
    ) {
        parent::__construct($pdo);
        $this->publisherRepository = $publisherRepository;
    }

    /**
     * Insert Magazine
     * @param Magazine $magazine    The magazine to insert
     * @return Magazine         The magazine inserted
     * @throws RepositoryException  If the insert fails         * 
     */
    public function insert(Magazine $magazine): Magazine {

        $queryMagazine =
            "INSERT INTO magazine
                (ObjectID,Title,Year,MagazineNumber,PublisherID) VALUES 
                (:ObjectID,:Title,:Year,:MagazineNumber,:PublisherID);";

        $queryObject =
            "INSERT INTO genericobject
                (ObjectID,Note,Url,Tag,Active,Erased)
                VALUES
                (:ObjectID,:Note,:Url,:Tag,:Active,:Erased)";
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindValue(':ObjectID', $magazine->ObjectID, PDO::PARAM_STR);
            $stmt->bindValue(':Note', $magazine->Note, PDO::PARAM_STR);
            $stmt->bindValue(':Url', $magazine->Url, PDO::PARAM_STR);
            $stmt->bindValue(':Tag', $magazine->Tag, PDO::PARAM_STR);
            $stmt->bindValue(':Active', $magazine->Active, PDO::PARAM_STR);
            $stmt->bindValue(':Erased', $magazine->Erased, PDO::PARAM_STR);

            $stmt->execute();

            $stmt = $this->pdo->prepare($queryMagazine);
            $stmt->bindParam("ObjectID", $magazine->ObjectID, PDO::PARAM_STR);
            $stmt->bindParam("Title", $magazine->Title, PDO::PARAM_STR);
            $stmt->bindParam("Year", $magazine->Year, PDO::PARAM_INT);
            $stmt->bindParam("MagazineNumber", $magazine->MagazineNumber, PDO::PARAM_INT);
            $stmt->bindParam("PublisherID", $magazine->Publisher->PublisherID, PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
            return $magazine;
        } catch (PDOException) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while inserting the magazine with id: {" . $magazine->ObjectID . "}");
        }
    }

    /**
     * Select magazine by id
     * @param string $ObjectID  The object id to select
     * @param ?bool $showErased
     * @return ?Magazine    The magazine selected, null if not found
     */
    public function selectById(string $ObjectID, ?bool $showErased = false): ?Magazine {
        $query = "SELECT * FROM magazine b 
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID 
            WHERE g.ObjectID = :ObjectID";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
        $stmt->execute();
        $magazine = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($magazine) {
            return $this->returnMappedObject($magazine);
        }
        return null;
    }

    /**
     * Select magazine by title
     * @param string $Title     The magazine title to select
     * @param ?bool $showErased
     * @return ?Magazine    The magazine selected, null if not found
     */
    public function selectByTitle(string $Title, ?bool $showErased = false): ?Magazine {
        $query = "SELECT * FROM magazine b
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID 
            WHERE Title LIKE Concat('%',:Title,'%')";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Title", $Title, PDO::PARAM_STR);
        $stmt->execute();
        $magazine = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($magazine) {
            return $this->returnMappedObject($magazine);
        }
        return null;
    }

    /**
     * Select by key
     * @param string $key The key given
     * @param ?bool $showErased
     * @return array   All magazines, empty array if no result
     */
    public function selectByKey(string $key,?bool $showErased = false): array {
        $query = "SELECT DISTINCT g.*,m.* FROM magazine m
            INNER JOIN genericobject g ON g.ObjectID = m.ObjectID
            INNER JOIN publisher p ON m.PublisherID = p.PublisherID
            WHERE m.Title LIKE :key OR
            m.MagazineNumber LIKE :key OR
            m.Year LIKE :key OR
            p.Name LIKE :key OR
            g.Note LIKE :key OR
            g.Tag LIKE :key";

        if (isset($showErased)) {
            $query .= " AND g.Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $key = '%'.$key.'%';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("key", $key, PDO::PARAM_STR);
        
        $stmt->execute();

        $arr_magazine = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_magazine;
    }

    /**
     * Select all magazines
     * @param ?bool $showErased
     * @return ?array   All magazines, null if no result
     */
    public function selectAll(?bool $showErased = false): ?array {
        $query = "SELECT * FROM magazine b
            INNER JOIN genericobject g ON g.ObjectID = b.ObjectID";

        if (isset($showErased)) {
            $query .= " WHERE Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->query($query);

        $arr_magazine = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_magazine;
    }

    /**
     * Update a magazine
     * @param Magazine $s   The magazine to update
     * @return Magazine     The magazine updated
     * @throws RepositoryException  If the update fails
     */
    public function update(Magazine $m): void {
        $queryMagazine =
            "UPDATE magazine
            SET Title = :Title,
            Year = :Year,
            MagazineNumber = :MagazineNumber,
            PublisherID = :PublisherID
            WHERE ObjectID = :ObjectID";

        $queryObject =
            "UPDATE genericobject
            SET Note = :Note,
            Url = :Url,
            Tag = :Tag,
            Active = :Active,
            Erased = :Erased
            WHERE ObjectID = :ObjectID";

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare($queryMagazine);
            $stmt->bindParam("Title", $m->Title, PDO::PARAM_STR);
            $stmt->bindParam("Year", $m->Year, PDO::PARAM_INT);
            $stmt->bindParam("MagazineNumber", $m->MagazineNumber, PDO::PARAM_INT);
            $stmt->bindParam("PublisherID", $m->Publisher->PublisherID, PDO::PARAM_INT);
            $stmt->bindParam("ObjectID", $m->ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $stmt = $this->pdo->prepare($queryObject);
            $stmt->bindParam("Note", $m->Note, PDO::PARAM_STR);
            $stmt->bindParam("Url", $m->Url, PDO::PARAM_STR);
            $stmt->bindParam("Tag", $m->Tag, PDO::PARAM_STR);
            $stmt->bindParam("Active", $m->Active, PDO::PARAM_STR);
            $stmt->bindParam("Erased", $m->Erased, PDO::PARAM_STR);
            $stmt->bindParam("ObjectID", $m->ObjectID, PDO::PARAM_STR);
            $stmt->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new RepositoryException("Error while updating the magazine with id: {" . $m->ObjectID . "}");
        }
    }

    /**
     * Delete a magazine
     * @param string $ObjectID  The object id to delete
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $ObjectID): void {
        $query =
            "UPDATE genericobject
            SET Erased = NOW()
            WHERE ObjectID = :ObjectID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("ObjectID", $ObjectID, PDO::PARAM_STR);
        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while deleting the magazine with id: {" . $ObjectID . "}");
        }
    }

    /**
     * Return a new instance of Magazine from an array
     * @param array $rawMagazine    The raw magazine object
     * @return Magazine The new instance of magazine with the fk filled with the result of selects
     */
    function returnMappedObject(array $rawMagazine): Magazine {
        return new Magazine(
            $rawMagazine["ObjectID"],
            $rawMagazine["Note"] ?? null,
            $rawMagazine["Url"] ?? null,
            $rawMagazine["Tag"] ?? null,
            strval($rawMagazine["Active"]),
            $rawMagazine["Erased"] ?? null,
            $rawMagazine["Title"],
            intval($rawMagazine["Year"]),
            intval($rawMagazine["MagazineNumber"]),
            $this->publisherRepository->selectById(intval($rawMagazine["PublisherID"]))
        );
    }
}
