<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ServiceException;
use App\Repository\UserRepository;
use App\Model\User;

class UserService {

    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Insert user
     * @param User $u   The user to insert
     * @return User     The user inserted
     * @throws RepositoryException  If the insert fails
     * @throws ServiceException     If the email is already user
     */
    public function insert(User $u): User {
        if ($this->userRepository->selectById($u->Email) != null)
            throw new ServiceException("Email already used!");

        return $this->userRepository->insert($u);
    }

    /**
     * Select by id
     * @param string $Email The email to select
     * @return User     The user selected
     * @throws ServiceException     If no user is found
     */
    public function selectById(string $email): User {
        $user = $this->userRepository->selectById($email);
        if ($user == null) throw new ServiceException("User not found");

        return $user;
    }

    /**
     * Select by credentials
     * @param string $Email The email to select
     * @param string $Password The password to select
     * @return User     The user selected
     * @throws ServiceException     If no user is found
     */
    public function selectByCredentials(string $Email, string $Password, bool $isAdmin = null): User {
        $user = $this->userRepository->selectByCredentials($Email, $Password, $isAdmin);
        if ($user == null) throw new ServiceException("User not found");

        return $user;
    }

    /**
     * Select all Users
     * @return array All the users
     * @throws ServiceException If no results
     */
    public function selectAll():array{

        $users = $this->userRepository->selectAll();

        if($users){
            return $users;
        }

        throw new ServiceException("No results");
    }

    /**
     * Update a user
     * @param User $u The user to update
     * @return User The user updated
     * @throws ServiceException If the user is not found
     * @throws RepositoryException If the update fails
     */
    public function update(User $u): User {
        if ($this->userRepository->selectById($u->Email) == null)
            throw new ServiceException("User not found!");

        return $this->userRepository->update($u);
    }

    /**
     * Delete a User by email
     * @param string $email The email to delete
     * @return User The user deleted
     * @throws ServiceException If the user is not found
     * @throws RepositoryException If the delete fails
     */
    public function delete(string $email): User {
        $user = $this->userRepository->selectById($email);
        if ($user == null)
            throw new ServiceException("User not found!");

        $this->userRepository->delete($email);
        return $user;
    }
}
