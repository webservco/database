<?php

declare(strict_types=1);

namespace WebServCo\Database\DataTransfer;

use WebServCo\DataTransfer\Contract\DataTransferInterface;

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
    /**
     * @readonly
     */
    public string $sqlStateErrorCode;
    /**
     * @readonly
     */
    public ?string $driverErrorCode;
    /**
     * @readonly
     */
    public ?string $driverErrorMessage;
    public function __construct(string $sqlStateErrorCode, ?string $driverErrorCode, ?string $driverErrorMessage)
    {
        // 0 "SQLSTATE error code (a five characters alphanumeric identifier defined in the ANSI SQL standard)."
        $this->sqlStateErrorCode = $sqlStateErrorCode;
        // 1 "Driver-specific error code."
        $this->driverErrorCode = $driverErrorCode;
        // 2 "Driver-specific error message."
        $this->driverErrorMessage = $driverErrorMessage;
    }
}
