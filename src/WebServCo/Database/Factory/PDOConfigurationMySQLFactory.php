<?php

declare(strict_types=1);

namespace WebServCo\Database\Factory;

use Override;
use WebServCo\Database\Contract\PDOConfigurationFactoryInterface;
use WebServCo\Database\DataTransfer\PDOConfiguration;

final class PDOConfigurationMySQLFactory implements PDOConfigurationFactoryInterface
{
    public function __construct(
        private string $host,
        private int $port,
        private string $dbname,
        private string $username,
        private string $password,
    ) {
    }

    #[Override]
    public function createPDOConfiguration(): PDOConfiguration
    {
        return new PDOConfiguration('mysql', $this->host, $this->port, $this->dbname, $this->username, $this->password);
    }
}
