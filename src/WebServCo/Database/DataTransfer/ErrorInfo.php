<?php

declare(strict_types=1);

namespace WebServCo\Database\DataTransfer;

use WebServCo\Data\Contract\Transfer\DataTransferInterface;

/**
 * Convenience object for PDO `errorInfo`
 *
 * `PDO::errorInfo()` (method)
 * `PDOException::errorInfo` (property)
 * `PDOStatement::errorInfo()` (method)
 *
 * Why nullables: "If the SQLSTATE error code is not set or there is no driver-specific error,
 * the elements following element 0 will be set to null."
 */
final class ErrorInfo implements DataTransferInterface
{
    public function __construct(
        // 0 "SQLSTATE error code (a five characters alphanumeric identifier defined in the ANSI SQL standard)."
        public readonly string $sqlStateErrorCode,
        // 1 "Driver-specific error code."
        public readonly ?string $driverErrorCode,
        // 2 "Driver-specific error message."
        public readonly ?string $driverErrorMessage,
    ) {
    }
}
