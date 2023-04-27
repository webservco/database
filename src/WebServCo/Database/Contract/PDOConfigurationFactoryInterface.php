<?php

declare(strict_types=1);

namespace WebServCo\Database\Contract;

use WebServCo\Database\DataTransfer\PDOConfiguration;

interface PDOConfigurationFactoryInterface
{
    public function createPDOConfiguration(): PDOConfiguration;
}
