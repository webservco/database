<?php

declare(strict_types=1);

namespace WebServCo\Database\Service;

use PDO;
use PDOStatement;
use UnexpectedValueException;
use WebServCo\Database\Contract\PDOServiceInterface;

use function is_array;

final class PDOService implements PDOServiceInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @return array<string,scalar|null>
     */
    public function fetchAssoc(PDOStatement $stmt): array
    {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (is_array($result)) {
            return $result;
        }

        // PDO statement returns false on both error and "no result".
        $this->validateReturn($stmt);

        // If we arrive here it means there were no result(s).
        return [];
    }

    public function prepareStatement(string $query): PDOStatement
    {
        $stmt = $this->pdo->prepare($query);
        if (!($stmt instanceof PDOStatement)) {
            throw new UnexpectedValueException('Error preparing statement.');
        }

        return $stmt;
    }

    public function validateReturn(PDOStatement $stmt): bool
    {
        /**
         * 0 = "SQLSTATE"
         * 1 = "Driver specific error code"
         * 2 = "Driver specific error message"
         */
        $errorInfo = $stmt->errorInfo();

        // "Successful completion", so no results
        if ($errorInfo[0] !== '00000') {
            throw new UnexpectedValueException('Statement error.');
        }

        return true;
    }
}
