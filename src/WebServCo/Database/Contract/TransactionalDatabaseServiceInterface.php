<?php

declare(strict_types=1);

namespace WebServCo\Database\Contract;

interface TransactionalDatabaseServiceInterface
{
    public function beginTransaction(): bool;

    public function commitTransaction(): bool;

    public function rollBackTransaction(): bool;
}
