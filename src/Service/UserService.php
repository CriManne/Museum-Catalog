<?php

declare(strict_types=1);

namespace App\Service;

use AbstractRepo\DataModels\FetchParams;
use AbstractRepo\Exceptions\ReflectionException;
use AbstractRepo\Exceptions\RepositoryException;
use App\DataModels\FetchableData;
use App\DataModels\User\UserResponse;
use App\Exception\ServiceException;
use App\Model\User;
use App\Repository\UserRepository;

class UserService {

    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Insert user
     * @param User $u   The user to insert
     * @throws RepositoryException  If the insert fails
     * @throws ServiceException     If the email is already user
     */
    public function insert(User $u): void {
        if ($this->userRepository->findById($u->Email) != null)
            throw new ServiceException("Email already used!");

        $this->userRepository->save($u);
    }

    /**
     * Select by id
     * @param string $Email The email to select
     * @return UserResponse     The user selected
     * @throws ServiceException     If no user is found
     */
    public function selectById(string $email): User {
        $user = $this->userRepository->findById($email);
        if (is_null($user)) throw new ServiceException("User not found");

        return $user;
    }

    /**
     * Select by credentials
     * @param string $Email The email to select
     * @param string $Password The password to select
     * @return UserResponse     The user selected
     * @throws ServiceException     If no user is found
     */
    public function selectByCredentials(string $Email, string $Password): UserResponse {
        $user = $this->userRepository->selectByCredentials($Email, $Password);
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
     * @throws RepositoryException
     * @throws ServiceException If no results
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
                        "Email" => $u->Email,
                        "Firstname" => $u->Firstname,
                        "Lastname" =>  $u->Lastname,
                        "Privilege" => $u->Privilege
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
     * @throws ServiceException If the user is not found
     * @throws RepositoryException If the update fails
     */
    public function update(User $u): void {
        $user = $this->userRepository->findById($u->Email);
        if (is_null($user)) {
            throw new ServiceException("User not found!");
        }

        $this->userRepository->update($u);
    }

    /**
     * Delete a User by email
     * @param string $email The email to delete
     * @throws ServiceException If the user is not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $email): void {
        $user = $this->userRepository->findById($email);
        if (is_null($user)) {
            throw new ServiceException("User not found!");
        }

        $this->userRepository->delete($email);
    }
}
