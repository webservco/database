<?php

declare(strict_types=1);

namespace WebServCo\Database\Factory;

use WebServCo\Database\Contract\PDOConfigurationFactoryInterface;
use WebServCo\Database\DataTransfer\PDOConfiguration;

final class PDOConfigurationMySQLFactory implements PDOConfigurationFactoryInterface
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

    public function createPDOConfiguration(): PDOConfiguration
    {
        return new PDOConfiguration('mysql', $this->host, $this->port, $this->dbname, $this->username, $this->password);
    }
}
