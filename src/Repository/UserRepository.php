<?php

    declare(strict_types=1);

    namespace App\Repository;

    use App\Models\User;
    use PDO;

    class UserRepository{

        public PDO $pdo;

        public function __construct(){
            $credentials = ['root',''];
            $this->pdo = new PDO(
                "mysql:host=localhost;dbname=mupin",
                $credentials[0],
                $credentials[1],
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }

        public function getUser(string $email,string $psw){
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE Email = :email AND Password = :psw");            
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