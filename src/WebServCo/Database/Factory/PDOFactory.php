<?php

declare(strict_types=1);

namespace WebServCo\Database\Factory;

use OutOfBoundsException;
use Override;
use PDO;
use WebServCo\Database\Contract\PDOFactoryInterface;
use WebServCo\Database\DataTransfer\PDOConfiguration;

use function sprintf;

/**
 * Initialize a PDO instance with opinionated settings.
 */
final class PDOFactory implements PDOFactoryInterface
{
    public function createPDO(PDOConfiguration $pdoConfiguration): PDO
    {
        return new PDO(
            // dsn
            $this->getDataSourceName(
                $pdoConfiguration->driverName,
                $pdoConfiguration->host,
                $pdoConfiguration->port,
                $pdoConfiguration->dbname,
            ),
            $pdoConfiguration->username,
            $pdoConfiguration->passsword,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => false,
            ],
        );
    }

    private function getDataSourceName(string $driverName, string $host, int $port, string $dbname): string
    {
        $this->validateDriverName($driverName);

        return sprintf('%s:host=%s;port=%d;dbname=%s;charset=%s', $driverName, $host, $port, $dbname, 'utf8mb4');
    }

    private function validateDriverName(string $driverName): bool
    {
        switch ($driverName) {
            case 'mysql':
                return true;
            default:
                throw new OutOfBoundsException('Unsupported driver.');
        }
    }
}
