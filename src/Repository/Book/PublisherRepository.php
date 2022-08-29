<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Repository\GenericRepository;
use App\Exception\RepositoryException;
use App\Model\Book\Publisher;
use PDO;
use PDOException;
use App\Util\ORM;

class PublisherRepository extends GenericRepository {

    /**
     * Insert a publisher
     * @param Publisher $publisher  The publisher to insert
     * @return Publisher    The publisher inserted
     * @throws RepositoryException  If the insert fails
     */
    public function insert(Publisher $publisher): Publisher {

        $query =
            "INSERT INTO publisher 
            (Name) VALUES 
            (:Name);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $publisher->Name, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $publisher->PublisherID = intval($this->pdo->lastInsertId());
            return $publisher;
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the publisher with name: {" . $publisher->Name . "}");
        }
    }

    /**
     * Select publisher by id
     * @param int $PublisherID  The publisher id
     * @param ?bool $showErased
     * @return ?Publisher   The publisher selected, null if not found         * 
     */
    public function selectById(int $PublisherID, ?bool $showErased = false): ?Publisher {
        $query = "SELECT * FROM publisher WHERE PublisherID = :PublisherID";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("PublisherID", $PublisherID, PDO::PARAM_INT);
        $stmt->execute();
        $publisher = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($publisher) {
            return ORM::getNewInstance(Publisher::class, $publisher);
        }
        return null;
    }

    /**
     * Select publisher by name
     * @param string $Name  The publisher name
     * @param ?bool $showErased
     * @return ?Publisher   The publisher selected,null if not found
     */
    public function selectByName(string $Name, ?bool $showErased = false): ?Publisher {
        $query = "SELECT * FROM publisher WHERE Name = :Name";

        if (isset($showErased)) {
            $query .= " AND Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Name", $Name, PDO::PARAM_STR);
        $stmt->execute();
        $publisher = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($publisher) {
            return ORM::getNewInstance(Publisher::class, $publisher);
        }
        return null;
    }

    /**
     * Select all publishers
     * @param ?bool $showErased
     * @return ?array   The selected publishers, null if no result
     */
    public function selectAll(?bool $showErased = false): ?array {
        $query = "SELECT * FROM publisher";

        if (isset($showErased)) {
            $query .= " WHERE Erased " . ($showErased ? "IS NOT NULL;" : "IS NULL;");
        }

        $stmt = $this->pdo->query($query);

        $arr_cpu = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $arr_cpu;
    }

    /**
     * Update a publisher
     * @param Publisher $p  The publisher to update
     * @return Publisher The publisher updated
     * @throws RepositoryException  If the update fails
     */
    public function update(Publisher $p): Publisher {
        $query =
            "UPDATE publisher 
            SET Name = :name
            WHERE PublisherID = :PublisherID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("name", $p->Name, PDO::PARAM_STR);
        $stmt->bindParam("PublisherID", $p->PublisherID, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return $p;
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the publisher with id: {" . $p->PublisherID . "}");
        }
    }

    /**
     * Delete a publisher
     * @param int $PublisherID  The publisher id to delete
     * @throws RepositoryException If the delete fails         * 
     */
    public function delete(int $PublisherID): void {
        $query =
            "UPDATE publisher          
            SET Erased = NOW()
            WHERE PublisherID = :PublisherID;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("PublisherID", $PublisherID, PDO::PARAM_INT);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the publisher with id: {" . $PublisherID . "}");
        }
    }
}
