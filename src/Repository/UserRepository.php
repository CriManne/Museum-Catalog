<?php

declare(strict_types=1);

namespace App\Repository;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\RepositoryException as AbstractRepositoryException;
use AbstractRepo\Repository\AbstractRepository;
use App\DataModels\User\UserResponse;
use App\Models\User;
use App\Util\ORM;

/**
 * @method User|null findById($id)
 */
class UserRepository extends AbstractRepository
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
        /**
         * @var User|null $user
         */
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
