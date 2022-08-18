<?php

    declare(strict_types=1);

    namespace App\Repository;

    use App\Exception\RepositoryException;
    use PDO;
    use App\Model\User;
    use PDOException;   
    use App\Util\ORM;

    class UserRepository extends GenericRepository{

        /**
         * Insert a user
         * @param User $u   The user to insert
         * @return User     The user inserted
         * @throws RepositoryException  If the insert fails
         */
        public function insert(User $u):User{

            $query = 
            "INSERT INTO user 
            (Email,password,firstname,lastname,privilege) VALUES 
            (:Email,:password,:firstname,:lastname,:privilege);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("Email",$u->Email,PDO::PARAM_STR);
            $stmt->bindParam("password",$u->Password,PDO::PARAM_STR);
            $stmt->bindParam("firstname",$u->firstname,PDO::PARAM_STR);
            $stmt->bindParam("lastname",$u->lastname,PDO::PARAM_STR);
            $stmt->bindParam("privilege",$u->Privilege,PDO::PARAM_STR);

            try{             
                $stmt->execute();
                return $u;
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the user with Email: {".$u->Email."}");
            }            
        }

        /**
         * Select a User
         * @param string $Email     The Email of the user to select
         * @return ?User            The User selected, null if not found
         */
        public function selectById(string $Email): ?User
        {            
            $query = "SELECT * FROM user WHERE Email = :Email";
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("Email",$Email,PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user){                
                return ORM::getNewInstance(User::class,$user);
            }
            return null;
        }

        /**
         * Select a user with his credentials
         * @param string $Email     The Email of the user
         * @param string $Password  The password of the user
         * @param bool $isAdmin     If set it will select only admins if true, only normal users otherwise
         * @return ?User            The user selected, null if not found         * 
         */
        public function selectByCredentials(string $Email,string $Password,bool $isAdmin = null): ?User{
            $query = "SELECT * FROM user WHERE Email = :Email AND Password = :Password";

            if(isset($isAdmin)){
                $query .= " AND Privilege = ".($isAdmin ? "1" : "0");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("Email",$Email,PDO::PARAM_STR);
            $stmt->bindParam("Password",$Password,PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user){
                return ORM::getNewInstance(User::class,$user);
            }
            return null;
        }

        /**
         * Select all users
         * @return ?array   All users, null if user table is empty
         */
        public function selectAll(): ?array{
            $query = "SELECT * FROM user";
            $stmt = $this->pdo->query($query);

            $users = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            

            return $users;
        }

        /**
         * Update a user
         * @param User $u   The user to update
         * @return User     The user updated
         * @throws RepositoryException  If the update fails
         */
        public function update(User $u): User
        {            
            $query = 
            "UPDATE user 
            SET Password = :Password,
            firstname = :firstname,
            lastname = :lastname,
            Privilege = :Privilege
            WHERE Email = :Email;";

            $stmt = $this->pdo->prepare($query);   
            $stmt->bindParam("Password",$u->Password,PDO::PARAM_STR);
            $stmt->bindParam("firstname",$u->firstname,PDO::PARAM_STR);
            $stmt->bindParam("lastname",$u->lastname,PDO::PARAM_STR);
            $stmt->bindParam("Privilege",$u->Privilege,PDO::PARAM_INT);
            $stmt->bindParam("Email",$u->Email,PDO::PARAM_STR);
            try{
                $stmt->execute();
                return $u;
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the user with Email: {".$u->Email."}");
            }
        }

        /**
         * Delete an user
         * @param string $Email     The Email of the user
         * @throws RepositoryException  If the delete fails
         */
        public function delete(string $Email): void
        {
            $query = 
            "DELETE FROM user             
            WHERE Email = :Email;";

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("Email",$Email,PDO::PARAM_STR);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the user with Email: {".$Email."}");
            }
        }
    }
?>