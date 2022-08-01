<?php
    declare(strict_types=1);

    namespace App\Service;

    use App\Exception\ServiceException;
    use App\Repository\UserRepository;
    use App\Model\User;

    class UserService{

        public UserRepository $userRepository;

        public function __construct(UserRepository $userRepository)
        {
            $this->userRepository = $userRepository;
        }

        public function insert(User $u):void{
            if($this->userRepository->selectById($u->Email) != null)
                throw new ServiceException("Email already used!");

            $this->userRepository->insert($u);
        }

        public function selectById(string $email): User{
            $user = $this->userRepository->selectById($email); 
            if($user == null) throw new ServiceException("User not found");

            return $user;
        }

        public function selectByCredentials(string $email,string $psw,bool $isAdmin = null): User{
            $user = $this->userRepository->selectByCredentials($email,$psw,$isAdmin);
            if($user == null) throw new ServiceException("User not found");

            return $user;
        }

        public function update(User $u):void{
            if($this->userRepository->selectById($u->Email) == null)
                throw new ServiceException("User not found!");

            $this->userRepository->update($u);
        }

        public function delete(string $email): void{
            if($this->userRepository->selectById($email) == null)
                throw new ServiceException("User not found!");

            $this->userRepository->delete($email);
        }
    }

?>