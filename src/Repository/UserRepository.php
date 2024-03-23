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
     * @param string $Email     The Email of the user
     * @param string $Password  The Password of the user
     * @return ?UserResponse            The user selected, null if not found         * 
     */
    public function selectByCredentials(string $Email, string $Password): ?UserResponse {
        $query = "SELECT Email,Password,Firstname,Lastname,Privilege FROM User WHERE BINARY Email = :Email";

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
}
