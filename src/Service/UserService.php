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

        public function insertUser(User $u):void{
            if($this->userRepository->selectById($u->email) != null)
                throw new ServiceException("Email already used!");

            $this->userRepository->insertUser($u);
        }

        public function selectById(string $email): ?User{
            return $this->userRepository->selectById($email);
        }

        public function selectByCredentials(string $email,string $psw,bool $isAdmin = false){
            return $this->userRepository->selectByCredentials($email,$psw,$isAdmin);
        }

        public function updateUser(User $u):void{
            if($this->userRepository->selectById($u->email) == null)
                throw new ServiceException("User not found!");

            $this->userRepository->updateUser($u);
        }

        public function deleteUser(string $email): void{
            if($this->userRepository->selectById($email) == null)
                throw new ServiceException("User not found!");

            $this->userRepository->deleteUser($email);
        }
    }

?>