<?php

declare(strict_types=1);

namespace App\Model\Response;

class UserResponse {

    public string $Email;
    public string $firstname;
    public string $lastname;
    public int $Privilege;

    public function __construct(
        string $Email,
        string $firstname,
        string $lastname,
        int $Privilege = 0
    ) {
        $this->Email = $Email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->Privilege = $Privilege;
    }
}
