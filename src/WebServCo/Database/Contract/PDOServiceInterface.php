<?php

declare(strict_types=1);

namespace WebServCo\Database\Contract;

use PDO;
use PDOException;
use PDOStatement;
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

    public function getErrorInfo(PDO|PDOException|PDOStatement $pdoObject): ?ErrorInfo;

    /**
     * Helper for `PDOStatement::fetch`
     * Fetches the next row from a result set
     * with PDO::FETCH_ASSOC and validation.
     *
     * @return array<string,scalar|null>
     */
    public function fetchAssoc(PDOStatement $stmt): array;

    /**
     * Helper for `PDO::prepare`.
     *
     * Why: make sure a PDOStatement is always returned.
     * (`PDO::prepare` can return false on error).
     */
    public function prepareStatement(string $query): PDOStatement;
}
