<?php

declare(strict_types=1);

namespace WebServCo\Database\Contract;

use PDOStatement;

interface PDOServiceInterface
{
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

     /**
     * Make sure PDO Statement returns an array when there are no errors.
     *
     * "In all cases, false is returned on failure."
     * However, false is also returned when there are no results.
     */
    public function validateReturn(PDOStatement $stmt): bool;
}
