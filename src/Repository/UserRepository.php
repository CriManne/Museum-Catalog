<?php

declare(strict_types=1);

namespace App\Repository;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\ReflectionException;
use AbstractRepo\Exceptions\RepositoryException;
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
     * @throws ReflectionException
     * @throws RepositoryException
     * @throws \ReflectionException
     */
    public function selectByCredentials(string $email, string $password): ?UserResponse {
        $user = $this->findFirst(new FetchParams(
           conditions: "email = :email",
           bind: [
               "email" => $email
            ]
        ));

        if($user){
            if(password_verify($password,$user->password)){
                unset($user->password);
                return ORM::getNewInstance(UserResponse::class, (array)$user);
            }
        }
        return null;
    }
}
