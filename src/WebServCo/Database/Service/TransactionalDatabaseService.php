<?php

declare(strict_types=1);

namespace WebServCo\Database\Service;

use OutOfBoundsException;
use Throwable;
use WebServCo\Database\Contract\PDOContainerInterface;
use WebServCo\Database\Contract\TransactionalDatabaseServiceInterface;

final class TransactionalDatabaseService implements TransactionalDatabaseServiceInterface
{
    private PDOContainerInterface $pdoContainer;
    /**
     * Note: avoid using PDO directly in class constructors,
     * it guarantees db connection open on initialization which is not always intended.
     * Instead always use PDO only as method arguments, and if it must be used in constructor,
     * use a container instead, which should only create the PDO object when invoked.
     * That way, the database connection should be opened only when actually used the first time.
     *
     * Use case: a script that runs for a long time and uses the database connection later in the run.
     * Prevents error: 2006 MySQL server has gone away
     */
    public function __construct(PDOContainerInterface $pdoContainer)
    {
        $this->pdoContainer = $pdoContainer;
    }

    public function beginTransaction(): bool
    {
        return $this->pdoContainer->getPDO()->beginTransaction();
    }

    public function commitTransaction(): bool
    {
        if (!$this->pdoContainer->getPDO()->inTransaction()) {
            throw new OutOfBoundsException('No transaction is currently active within the driver.');
        }

        try {
            return $this->pdoContainer->getPDO()->commit();
        } catch (Throwable $e) {
            $this->pdoContainer->getPDO()->rollBack();

            throw $e;
        }
    }

    public function rollBackTransaction(): bool
    {
        return $this->pdoContainer->getPDO()->rollBack();
    }
}
