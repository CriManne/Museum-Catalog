<?php

    declare(strict_types=1);

    namespace App\Model;

    use DateTime;

    class User{

        public string $Email;
        public string $Password;
        public string $firstname;
        public string $lastname;
        public int $Privilege;
        public ?DateTime $Erased;

        public function __construct(
            string $Email,
            string $Password,
            string $firstname,
            string $lastname,
            int $Privilege,
            ?DateTime $Erased
        ){
            $this->Email = $Email;
            $this->Password = $Password;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->Privilege = $Privilege;
            $this->Erased = $Erased;
        }

    }
?>