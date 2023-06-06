<?php

declare(strict_types=1);

namespace WebServCo\Database\Container;

use PDO;
use WebServCo\Database\Contract\PDOConfigurationFactoryInterface;
use WebServCo\Database\Contract\PDOContainerInterface;
use WebServCo\Database\Contract\PDOFactoryInterface;
use WebServCo\Database\Contract\PDOServiceInterface;
use WebServCo\Database\Service\PDOService;

final class PDOContainer implements PDOContainerInterface
{
    private ?PDO $pdo = null;
    private ?PDOServiceInterface $pdoService = null;

    public function __construct(
        private PDOConfigurationFactoryInterface $pdoConfigurationFactory,
        private PDOFactoryInterface $pdoFactory,
    ) {
    }

    public function createPDO(): PDO
    {
        return $this->pdoFactory->createPDO(
            $this->pdoConfigurationFactory->createPDOConfiguration(),
        );
    }

    public function getPDO(): PDO
    {
        if ($this->pdo === null) {
            $this->pdo = $this->createPDO();
        }

        return $this->pdo;
    }

    public function getPDOService(): PDOServiceInterface
    {
        if ($this->pdoService === null) {
            $this->pdoService = new PDOService();
        }

        return $this->pdoService;
    }
}
