<?php

declare(strict_types=1);

namespace WebServCo\Database\Contract;

use PDO;
use WebServCo\Database\DataTransfer\PDOConfiguration;

interface PDOFactoryInterface
{
    public function createPDO(PDOConfiguration $pdoConfiguration): PDO;
}
