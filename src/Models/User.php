<?php

declare(strict_types=1);

namespace App\Models;

use AbstractRepo\Attributes\Entity;
use AbstractRepo\Attributes\PrimaryKey;
use AbstractRepo\Attributes\Searchable;
use AbstractRepo\Interfaces;

#[Entity('User')]
class User implements Interfaces\IModel
{
    /**
     * Levels of privilege
     */
    public const int PRIVILEGE_ADMIN       = 0;
    public const int PRIVILEGE_SUPER_ADMIN = 1;

    /**
     * The key of the session array where the email is stored
     */
    public const string SESSION_EMAIL_KEY = 'user_email';

    /**
     * The key of the session array where the privilege is stored
     */
    public const string SESSION_PRIVILEGE_KEY = 'privilege';

    public function __construct(
        #[PrimaryKey]
        #[Searchable]
        public string $email,
        public string $password,
        #[Searchable]
        public string $firstname,
        #[Searchable]
        public string $lastname,
        public int    $privilege = self::PRIVILEGE_ADMIN
    )
    {
    }
}
