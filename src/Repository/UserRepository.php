<?php

    declare(strict_types=1);

    namespace App\Repository;

    use App\Exception\RepositoryException;
    use PDO;
    use App\Model\User;
    use PDOException;   
    use App\Util\ORM;

    class UserRepository extends GenericRepository{

        //INSERT
        public function insert(User $u):void{

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
            $query = "SELECT * FROM user WHERE Email = :email";
            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("email",$email,PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user){                
                return ORM::getNewInstance(User::class,$user);
            }
            return null;
        }

        public function selectByCredentials(string $email,string $psw,bool $isAdmin = null): ?User{
            $query = "SELECT * FROM user WHERE Email = :email AND Password = :psw";

            if(isset($isAdmin)){
                $query .= " AND Privilege = ".($isAdmin ? "1" : "0");
            }           

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("email",$email,PDO::PARAM_STR);
            $stmt->bindParam("psw",$psw,PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($user){
                return ORM::getNewInstance(User::class,$user);
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
        public function update(User $u): void
        {            
            $query = 
            "UPDATE user 
            SET Password = :password,
            firstname = :firstname,
            lastname = :lastname,
            Privilege = :privilege
            WHERE Email = :email;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("password",$u->Password,PDO::PARAM_STR);
            $stmt->bindParam("firstname",$u->firstname,PDO::PARAM_STR);
            $stmt->bindParam("lastname",$u->lastname,PDO::PARAM_STR);
            $stmt->bindParam("privilege",$u->Privilege,PDO::PARAM_STR);
            $stmt->bindParam("email",$u->Email,PDO::PARAM_STR);
            try{             
                $stmt->execute();
            }catch(PDOException $e){
                throw new RepositoryException("Error while updating the user with email: {".$u->email."}");
            }
        }

        //DELETE
        public function delete(string $email): void
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