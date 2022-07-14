<?php

    declare(strict_types=1);

    namespace Mupin\Repository;

    use PDO;
    use Mupin\Model\User;    

    class UserRepository extends MupinRepository{

        public function __construct(PDO $pdo)
        {
            parent::__construct($pdo);            
        }

        //CREATE
        public function createObject(User $u):bool{

            


            return true;
        }

        //READ
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
        public function updateObject(object $obj): bool
        {
            
            return true;
        }

        //DELETE
        public function deleteById(mixed $id): bool
        {
            
            return true;
        }

        public function getUser(string $email,string $psw,bool $isAdmin = false){
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

    }