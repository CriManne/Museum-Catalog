<?php

declare(strict_types=1);

namespace App\Service;

use AbstractRepo\Exceptions\ReflectionException;
use App\DataModels\FetchableData;
use App\DataModels\User\UserResponse;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;
use App\Exception\RepositoryException;

class UserService {

    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Insert user
     * @param User $u The user to insert
     * @throws ReflectionException
     * @throws ServiceException If the email is already user
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function insert(User $u): void {
        if ($this->userRepository->findById($u->email) != null)
            throw new ServiceException("email already used!");

        $this->userRepository->save($u);
    }

    /**
     * Select by id
     * @param string $email The email to select
     * @return User The user selected
     * @throws ReflectionException
     * @throws ServiceException If no user is found
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function selectById(string $email): User {
        $user = $this->userRepository->findById($email);
        if (is_null($user)) throw new ServiceException("User not found");

        return $user;
    }

    /**
     * Select by credentials
     * @param string $email The email to select
     * @param string $password The password to select
     * @return UserResponse     The user selected
     * @throws ServiceException     If no user is found
     */
    public function selectByCredentials(string $email, string $password): UserResponse {
        $user = $this->userRepository->selectByCredentials($email, $password);
        if (is_null($user)) throw new ServiceException("Wrong credentials");

        return $user;
    }

    /**
     * Select all Users
     * @param int|null $page
     * @param int|null $itemsPerPage
     * @param string|null $query
     * @return FetchableData|array All the users
     * @throws ReflectionException
     * @throws ServiceException If no results
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function selectAll(?int $page, ?int $itemsPerPage, ?string $query): FetchableData|array
    {
        $users = $this->userRepository->findByQuery(
            query: $query,
            page: $page,
            itemsPerPage: $itemsPerPage
        );

        if (!$users) {
            throw new ServiceException("No results");
        }

        if (!is_array($users)) {
            return new FetchableData(
                page: $users->getCurrentPage(),
                itemsPerPage: $users->getItemsPerPage(),
                totalPages: $users->getTotalPages(),
                data:  array_map(
                    fn(User $u) => [
                        "email" => $u->email,
                        "Firstname" => $u->firstname,
                        "Lastname" =>  $u->lastname,
                        "privilege" => $u->privilege
                    ],
                    $users->getData()
                )
            );
        }

        return $users;
    }

    /**
     * Update a user
     * @param User $u The user to update
     * @throws ReflectionException
     * @throws ServiceException If the user is not found
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function update(User $u): void {
        $user = $this->userRepository->findById($u->email);
        if (is_null($user)) {
            throw new ServiceException("User not found!");
        }

        $this->userRepository->update($u);
    }

    /**
     * Delete a User by email
     * @param string $email The email to delete
     * @throws ReflectionException
     * @throws ServiceException If the user is not found
     * @throws \AbstractRepo\Exceptions\RepositoryException
     * @throws \ReflectionException
     */
    public function delete(string $email): void {
        $user = $this->userRepository->findById($email);
        if (is_null($user)) {
            throw new ServiceException("User not found!");
        }

        $this->userRepository->delete($email);
    }
}
