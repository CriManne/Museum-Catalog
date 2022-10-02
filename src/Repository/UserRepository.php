<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\RepositoryException;
use App\Model\Response\UserResponse;
use PDO;
use App\Model\User;
use PDOException;
use App\Util\ORM;

class UserRepository extends GenericRepository {

    /**
     * Insert a user
     * @param User $u   The user to insert
     * @throws RepositoryException  If the insert fails
     */
    public function insert(User $u): void {

        $query =
            "INSERT INTO user 
            (Email,Password,firstname,lastname,Privilege) VALUES 
            (:Email,:Password,:firstname,:lastname,:Privilege);";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Email", $u->Email, PDO::PARAM_STR);
        $stmt->bindParam("Password", $u->Password, PDO::PARAM_STR);
        $stmt->bindParam("firstname", $u->firstname, PDO::PARAM_STR);
        $stmt->bindParam("lastname", $u->lastname, PDO::PARAM_STR);
        $stmt->bindParam("Privilege", $u->Privilege, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException) {
            throw new RepositoryException("Error while inserting the user with Email: {" . $u->Email . "}");
        }
    }

    /**
     * Select a User
     * @param string $Email     The Email of the user to select
     * @return ?UserResponse            The User selected, null if not found
     */
    public function selectById(string $Email): ?UserResponse {
        $query = "SELECT Email,firstname,lastname,Privilege FROM user WHERE BINARY Email = :Email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Email", $Email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return ORM::getNewInstance(UserResponse::class, $user);
        }
        return null;
    }

    /**
     * Select a user with his credentials
     * @param string $Email     The Email of the user
     * @param string $Password  The Password of the user
     * @return ?UserResponse            The user selected, null if not found         * 
     */
    public function selectByCredentials(string $Email, string $Password): ?UserResponse {
        $query = "SELECT Email,Password,firstname,lastname,Privilege FROM user WHERE BINARY Email = :Email";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Email", $Email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user){
            if(password_verify($Password,$user["Password"])){
                unset($user["Password"]);
                return ORM::getNewInstance(UserResponse::class, $user);
            }
        }
        return null;
    }

    /**
     * Select all users
     * @return ?array   All users, null if user table is empty
     */
    public function selectAll(): ?array {
        $query = "SELECT Email,firstname,lastname,Privilege FROM user ORDER BY Email ASC";

        $stmt = $this->pdo->query($query);

        $users = $stmt->fetchAll(PDO::FETCH_CLASS);

        return $users;
    }

    /**
     * Update a user
     * @param User $u   The user to update
     * @throws RepositoryException  If the update fails
     */
    public function update(User $u): void {
        $query =
            "UPDATE user 
            SET Password = :Password,
            firstname = :firstname,
            lastname = :lastname
            WHERE Email = :Email;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Password", $u->Password, PDO::PARAM_STR);
        $stmt->bindParam("firstname", $u->firstname, PDO::PARAM_STR);
        $stmt->bindParam("lastname", $u->lastname, PDO::PARAM_STR);
        $stmt->bindParam("Email", $u->Email, PDO::PARAM_STR);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while updating the user with Email: {" . $u->Email . "}");
        }
    }

    /**
     * Delete an user
     * @param string $Email     The Email of the user
     * @throws RepositoryException  If the delete fails
     */
    public function delete(string $Email): void {
        $query =
            "DELETE FROM user             
            WHERE Email = :Email;";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("Email", $Email, PDO::PARAM_STR);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new RepositoryException("Error while deleting the user with Email: {" . $Email . "}");
        }
    }
}
