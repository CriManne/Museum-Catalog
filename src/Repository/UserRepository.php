<?php

declare(strict_types=1);

namespace App\Repository;

use AbstractRepo\Interfaces;
use AbstractRepo\Repository;
use App\DataModels\User\UserResponse;
use App\Model\User;
use App\Util\ORM;
use PDO;

class UserRepository extends Repository\AbstractRepository implements Interfaces\IRepository {

    public static function getModel(): string
    {
        return User::class;
    }

    /**
     * Select a user with his credentials
     * @param string $email
     * @param string $password
     * @return ?UserResponse            The user selected, null if not found
     */
    public function selectByCredentials(string $email, string $password): ?UserResponse {
        $query = "SELECT email,password,firstname,lastname,privilege FROM User WHERE email = :email";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam("email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user){
            if(password_verify($password,$user["password"])){
                unset($user["Password"]);
                return ORM::getNewInstance(UserResponse::class, $user);
            }
        }
        return null;
    }
}
