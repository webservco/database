<?php

declare(strict_types=1);

namespace WebServCo\Database\Contract;

use PDO;
use PDOException;
use PDOStatement;
use Throwable;
use WebServCo\Database\DataTransfer\ErrorInfo;

interface PDOServiceInterface
{
    /**
     * Make sure PDO Statement does not contain an error.
     *
     * Check errorInfo and throw an exception if there was any error.
     *
     * Use case: `PDOStatement.fetch` returns false on failure,
     * and also return false if there are no results.
     */
    public function assertNoError(PDOStatement $stmt): bool;

    /**
     * Helper for `PDOStatement::fetch`
     * Fetches the next row from a result set
     * with PDO::FETCH_ASSOC and validation.
     *
     * @return array<string,scalar|null>
     */
    public function fetchAssoc(PDOStatement $stmt): array;

    public function getErrorInfo(PDO|PDOException|PDOStatement $pdoObject): ?ErrorInfo;

    /**
     * Helper for `PDO::lastInsertId`
     *
     * Use case: `PDO::lastInsertId` can also return false,
     * however there is no info in the documentation about in which situation it returns false.
     * https://www.php.net/manual/en/pdo.lastinsertid.php
     *
     * Make sure a string is always returned.
     */
    public function getLastInsertId(PDO $pdo, ?string $sequenceObjectName = null): string;

    /**
     * Check if error is recoverable.
     *
     * Use case: check if transaction can be retried.
     * References:
     * https://en.wikipedia.org/wiki/SQLSTATE
     * https://mariadb.com/kb/en/mariadb-error-codes/
     */
    public function isRecoverableError(Throwable $throwable): bool;

    /**
     * Helper for `PDO::prepare`.
     *
     * Why: make sure a PDOStatement is always returned.
     * (`PDO::prepare` can return false on error).
     */
    public function prepareStatement(PDO $pdo, string $query): PDOStatement;
}
