<?php

declare(strict_types=1);

namespace WebServCo\Database\Contract;

use PDO;

interface PDOContainerInterface
{
    public function getPDO(): PDO;

    public function getPDOService(): PDOServiceInterface;
}
