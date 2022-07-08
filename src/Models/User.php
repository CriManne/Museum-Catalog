<?php

    declare(strict_types=1);

    namespace App\Models;

    use DateTime;

    class User{

        public string $email;
        public string $psw;
        public string $firstname;
        public string $lastname;
        public int $privilege;
        public ?DateTime $erased;

        public function __construct(
            string $email,
            string $psw,
            string $firstname,
            string $lastname,
            int $privilege,
            ?DateTime $erased
        ){
            $this->email = $email;
            $this->psw = $psw;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->privilege = $privilege;
            $this->erased = $erased;
        }

    }
?>