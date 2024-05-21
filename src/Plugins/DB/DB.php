<?php

namespace App\Plugins\DB;

use App\Exception\DatabaseException;
use App\Plugins\Injection\DIC;
use PDO;
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
        $pdo = self::getPdoOrThrow();

        try {
            if ($pdo->inTransaction()) {
                throw new DatabaseException("A transaction is already active!");
            }
            $pdo->beginTransaction();
        } catch (Throwable $e) {
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
        $pdo = self::getPdoOrThrow();

        try {
            if (!$pdo->inTransaction()) {
                throw new DatabaseException("No active transactions!");
            }
            $pdo->rollBack();
        } catch (Throwable $e) {
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
        $pdo = self::getPdoOrThrow();

        try {
            if (!$pdo->inTransaction()) {
                throw new DatabaseException("No active transactions!");
            }
            $pdo->commit();
        } catch (Throwable $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * Get the pdo or throw the Database exception if not found.
     * @return PDO
     * @throws DatabaseException
     */
    private static function getPdoOrThrow(): PDO
    {
        $pdo = DIC::getPdo();

        if (!$pdo) {
            throw new DatabaseException("No PDO object set by the container");
        }

        return $pdo;
    }
}