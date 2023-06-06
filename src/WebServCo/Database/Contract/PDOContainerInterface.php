<?php

declare(strict_types=1);

namespace WebServCo\Database\Contract;

use PDO;

interface PDOContainerInterface
{
    /**
     * Returns a new PDO instance, created on the fly.
     */
    public function createPDO(): PDO;

    /**
     * Returns the stored PDO instance, created if not already existing.
     */
    public function getPDO(): PDO;

    public function getPDOService(): PDOServiceInterface;
}
