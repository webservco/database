<?php

declare(strict_types=1);

namespace WebServCo\Database\Factory;

use WebServCo\Database\Container\PDOContainer;

final class PDOContainerMySQLFactory
{
    private string $host;
    private int $port;
    private string $dbname;
    private string $username;
    private string $password;
    public function __construct(string $host, int $port, string $dbname, string $username, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
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
