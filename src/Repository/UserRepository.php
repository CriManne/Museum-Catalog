<?php

declare(strict_types=1);

namespace App\Repository;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\ReflectionException;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use AbstractRepo\Interfaces;
use AbstractRepo\Repository;
use App\DataModels\User\UserResponse;
use App\Model\User;
use App\Util\ORM;

class UserRepository extends Repository\AbstractRepository
{
    public static function getModel(): string
    {
        return User::class;
    }

    /**
     * Select a user with his credentials
     * @param string $email
     * @param string $password
     * @return ?UserResponse            The user selected, null if not found
     * @throws AbstractRepositoryException
     * @throws \ReflectionException
     */
    public function findByCredentials(string $email, string $password): ?UserResponse
    {
        $user = $this->findFirst(new FetchParams(
            conditions: "email = :email",
            bind: [
                "email" => $email
            ]
        ));

        if ($user) {
            if (password_verify($password, $user->password)) {
                unset($user->password);
                return ORM::getNewInstance(UserResponse::class, (array)$user);
            }
        }
        return null;
    }
}
