<?php

    declare(strict_types=1);

    namespace Mupin\Repository;

    use Mupin\Exceptions\RepositoryException;
    use PDO;
    use Mupin\Model\User;
    use PDOException;   

    class UserRepository extends GenericRepository{

        public function __construct(PDO $pdo)
        {
            parent::__construct($pdo);            
        }

        //INSERT
        public function insertUser(User $u):void{

            $query = 
            "INSERT INTO user 
            (email,password,firstname,lastname,privilege) VALUES 
            (:email,:password,:firstname,:lastname,:privilege);";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("email",$u->email,PDO::PARAM_STR);
            $stmt->bindParam("password",$u->psw,PDO::PARAM_STR);
            $stmt->bindParam("firstname",$u->firstname,PDO::PARAM_STR);
            $stmt->bindParam("lastname",$u->lastname,PDO::PARAM_STR);
            $stmt->bindParam("privilege",$u->privilege,PDO::PARAM_STR);

            try{             
                $stmt->execute();
            }catch(PDOException){
                throw new RepositoryException("Error while inserting the user with email: {".$u->email."}");
            }            
        }

        //SELECT
        public function selectById(string $email): ?User
        {            
            $query = "SELECT * FROM user WHERE Email = :email";
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

        public function selectByCredentials(string $email,string $psw,bool $isAdmin = false): ?User{
            $query = "SELECT * FROM user WHERE Email = :email AND Password = :psw".($isAdmin ? " AND privilege = 1" : ""); 
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

        //UPDATE
        public function updateUser(User $u): void
        {            
            $query = 
            "UPDATE user 
            SET password = :password,
            firstname = :firstname,
            lastname = :lastname,
            privilege = :privilege,
            erased = :erased 
            WHERE email = :email;";

            $stmt = $this->pdo->prepare($query);            
            $stmt->bindParam("password",$u->psw,PDO::PARAM_STR);
            $stmt->bindParam("firstname",$u->firstname,PDO::PARAM_STR);
            $stmt->bindParam("lastname",$u->lastname,PDO::PARAM_STR);
            $stmt->bindParam("privilege",$u->privilege,PDO::PARAM_STR);
            $stmt->bindParam("erased",$u->erased);
            $stmt->bindParam("email",$u->email,PDO::PARAM_STR);
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