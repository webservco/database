<?php

declare(strict_types=1);

namespace WebServCo\Database\Factory;

use WebServCo\Database\Container\PDOContainer;

final class PDOContainerMySQLFactory
{
    public function __construct(
        private string $host,
        private int $port,
        private string $dbname,
        private string $username,
        private string $password,
    ) {
    }

    public function createPDOContainer(): PDOContainer
    {
        return new PDOContainer(
            new PDOConfigurationMySQLFactory(
                $this->host,
                $this->port,
                $this->dbname,
                $this->username,
                $this->password,
            ),
            new PDOFactory(),
        );
    }
}
