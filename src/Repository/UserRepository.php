<?php

    declare(strict_types=1);

    namespace App\Repository;

    use App\Exception\RepositoryException;
    use PDO;
    use App\Model\User;
    use PDOException;   

    class UserRepository extends GenericRepository{

        //INSERT
        public function insertUser(User $u):void{

            $query = 
            "INSERT INTO user 
            (email,password,firstname,lastname,privilege) VALUES 
            (:email,:password,:firstname,:lastname,:privilege);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("email",$u->Email,PDO::PARAM_STR);
            $stmt->bindParam("password",$u->Password,PDO::PARAM_STR);
            $stmt->bindParam("firstname",$u->firstname,PDO::PARAM_STR);
            $stmt->bindParam("lastname",$u->lastname,PDO::PARAM_STR);
            $stmt->bindParam("privilege",$u->Privilege,PDO::PARAM_STR);

            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the user with email: {".$u->Email."}");
            }            
        }

        //SELECT
        public function selectById(string $email): ?User
        {            
            $query = "SELECT * FROM user WHERE Email = :email AND Erased IS NOT NULL";
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("email",$email,PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();
            if($user){
                return new User(
                    $user["Email"],
                    $user["Password"],
                    $user["firstname"],
                    $user["lastname"],
                    $user["Privilege"],
                    $user["Erased"]
                );
            }
            return null;
        }

        public function selectByCredentials(string $email,string $psw,bool $isAdmin = null): ?User{
            $query = "SELECT * FROM user WHERE Email = :email AND Password = :psw AND Erased IS NOT NULL";

            if(isset($isAdmin)){
                $query .= " AND Privilege = ".($isAdmin ? "1" : "0");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("email",$email,PDO::PARAM_STR);
            $stmt->bindParam("psw",$psw,PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch();
            if($user){
                return new User(
                    $user["Email"],
                    $user["Password"],
                    $user["firstname"],
                    $user["lastname"],
                    $user["Privilege"],
                    $user["Erased"]
                );
            }
            return null;
        }

        public function selectAll(): ?array{
            $query = "SELECT * FROM user";
            $stmt = $this->pdo->query($query);

            $users = $stmt->fetchAll(PDO::FETCH_CLASSTYPE);            

            return $users;
        }

        //UPDATE
        public function updateUser(User $u): void
        {            
            $query = 
            "UPDATE user 
            SET Password = :password,
            firstname = :firstname,
            lastname = :lastname,
            Privilege = :privilege,
            Erased = :erased 
            WHERE Email = :email;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("password",$u->Password,PDO::PARAM_STR);
            $stmt->bindParam("firstname",$u->firstname,PDO::PARAM_STR);
            $stmt->bindParam("lastname",$u->lastname,PDO::PARAM_STR);
            $stmt->bindParam("privilege",$u->Privilege,PDO::PARAM_STR);
            $stmt->bindParam("erased",$u->Erased);
            $stmt->bindParam("email",$u->Email,PDO::PARAM_STR);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the user with email: {".$u->email."}");
            }
        }

        //DELETE
        public function deleteUser(string $email): void
        {
            $query = 
            "DELETE FROM user             
            WHERE email = :email;";

            $stmt = $this->pdo->prepare($query);                        
            $stmt->bindParam("email",$email,PDO::PARAM_STR);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while deleting the user with email: {".$email."}");
            }
        }
    }
?>