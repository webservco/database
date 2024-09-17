<?php

declare(strict_types=1);

namespace WebServCo\Database\Factory;

use WebServCo\Database\Container\PDOContainer;
use WebServCo\Database\Contract\PDOContainerFactoryInterface;
use WebServCo\Database\Contract\PDOContainerInterface;

final class PDOContainerMySQLFactory implements PDOContainerFactoryInterface
{
    public function __construct(
        private string $host,
        private int $port,
        private string $dbname,
        private string $username,
        private string $password,
    ) {
    }

    public function createPDOContainer(): PDOContainerInterface
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
