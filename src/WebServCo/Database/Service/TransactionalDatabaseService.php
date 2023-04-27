<?php

declare(strict_types=1);

namespace WebServCo\Database\Service;

use OutOfBoundsException;
use PDO;
use Throwable;
use WebServCo\Database\Contract\TransactionalDatabaseServiceInterface;

final class TransactionalDatabaseService implements TransactionalDatabaseServiceInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commitTransaction(): bool
    {
        if (!$this->pdo->inTransaction()) {
            throw new OutOfBoundsException('No transaction is currently active within the driver.');
        }

        try {
            return $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();

            throw $e;
        }
    }

    public function rollBackTransaction(): bool
    {
        return $this->pdo->rollBack();
    }
}
