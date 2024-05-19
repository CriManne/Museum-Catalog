<?php

namespace App\Plugins\DB;

use App\Exception\DatabaseException;
use App\Plugins\Injection\DIC;
use Throwable;

class DB
{
    /**
     * Begins the transaction
     *
     * @return void
     * @throws DatabaseException
     */
    public static function begin(): void
    {
        try {
            if (DIC::getPdo()->inTransaction()) {
                throw new DatabaseException("A transaction is already active!");
            }
            DIC::getPdo()->beginTransaction();
        }catch (Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * Rollback the active transaction
     * @return void
     * @throws DatabaseException
     */
    public static function rollback(): void
    {
        try {
            if (!DIC::getPdo()->inTransaction()) {
                throw new DatabaseException("No active transactions!");
            }
            DIC::getPdo()->rollBack();
        }catch (Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * Commit the active transaction
     *
     * @return void
     * @throws DatabaseException
     */
    public static function commit(): void
    {
        try {
            if (!DIC::getPdo()->inTransaction()) {
                throw new DatabaseException("No active transactions!");
            }
            DIC::getPdo()->commit();
        }catch (Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
}