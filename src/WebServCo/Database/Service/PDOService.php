<?php

declare(strict_types=1);

namespace WebServCo\Database\Service;

use Override;
use PDO;
use PDOException;
use PDOStatement;
use Throwable;
use UnexpectedValueException;
use WebServCo\Database\Contract\PDOServiceInterface;
use WebServCo\Database\DataTransfer\ErrorInfo;

use function array_key_exists;
use function count;
use function implode;
use function is_array;
use function is_int;
use function is_string;

final class PDOService implements PDOServiceInterface
{
    #[Override]
    public function assertNoError(PDOStatement $stmt): bool
    {
        $errorInfo = $this->getErrorInfo($stmt);

        if ($errorInfo === null) {
            // No error info => no error.
            return true;
        }

        // '00000' = "Successful completion" . https://en.wikipedia.org/wiki/SQLSTATE
        if ($errorInfo->sqlStateErrorCode === '00000') {
            return true;
        }

        // If we arrive here it means that the SQL state error code exists and is not the "not error" one.
        throw new UnexpectedValueException('Statement error.');
    }

    /**
     * @param array<int,scalar|null> $array
     */
    #[Override]
    public function generatePlaceholdersString(array $array): string
    {
        $data = [];
        for ($i = 0; $i < count($array); $i += 1) {
            $data[] = '?';
        }

        return implode(',', $data);
    }

    /**
     * @return array<string,scalar|null>
     */
    #[Override]
    public function fetchAssoc(PDOStatement $stmt): array
    {
        /**
         * @var array<string,scalar|null>|false $result
         */
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($result)) {
            return $result;
        }

        // PDO statement returns false on both error and "no result".
        $this->assertNoError($stmt);

        // If we arrive here it means there were no result(s).
        return [];
    }

    #[Override]
    public function getErrorInfo(PDO|PDOException|PDOStatement $pdoObject): ?ErrorInfo
    {
        $errorInfoArray = $this->getErrorInfoArray($pdoObject);

        if (!array_key_exists(0, $errorInfoArray)) {
            // No error info.
            return null;
        }

        $sqlStateErrorCode = (string) $errorInfoArray[0];

        if ($sqlStateErrorCode === '') {
            throw new UnexpectedValueException('SQL state error code is empty.');
        }

        return new ErrorInfo(
            $sqlStateErrorCode,
            $this->parseErrorInfoValueByIndex($errorInfoArray, 1),
            $this->parseErrorInfoValueByIndex($errorInfoArray, 2),
        );
    }

    #[Override]
    public function getLastInsertId(PDO $pdo, ?string $sequenceObjectName = null): string
    {
        $result = $pdo->lastInsertId($sequenceObjectName);

        /**
         * Psalm false positive:
         * ERROR: TypeDoesNotContainType - string does not contain false (see https://psalm.dev/056)
         * However lastInsertId can also return false as per documentation.
         *
         * @psalm-suppress TypeDoesNotContainType
         */
        if ($result === false) {
            throw new UnexpectedValueException('lastInsertId is false.');
        }

        return $result;
    }

    #[Override]
    public function isRecoverableError(Throwable $throwable): bool
    {
        if (!$throwable instanceof PDOException) {
            // Not a PDO exception.
            return false;
        }

        $errorInfo = $this->getErrorInfo($throwable);

        if ($errorInfo === null) {
            // There is a PDO exception, but no error info. Unusual situation, however technically possible.
            return false;
        }

        return $this->isRecoverableErrorMariadb($errorInfo);
    }

    #[Override]
    public function prepareStatement(PDO $pdo, string $query): PDOStatement
    {
        $stmt = $pdo->prepare($query);
        if (!($stmt instanceof PDOStatement)) {
            throw new UnexpectedValueException('Error preparing statement.');
        }

        return $stmt;
    }

    /**
     * Get the errorInfo array based on the input.
     *
     * Return is nullable because the property `errorInfo` in PDOException is nullable.
     * https://www.php.net/manual/en/class.pdoexception.php
     *
     * @return array<int,int|string|null>
     */
    private function getErrorInfoArray(PDO|PDOException|PDOStatement $pdoObject): array
    {
        switch (true) {
            case $pdoObject instanceof PDOException:
                return $this->parseOriginalErrorInfo($pdoObject->errorInfo);
            default:
                // \PDO, \PDOStatement
                return $this->parseOriginalErrorInfo($pdoObject->errorInfo());
        }
    }

    private function isRecoverableErrorMariadb(ErrorInfo $errorInfo): bool
    {
        return match ($errorInfo->sqlStateErrorCode) {
            // '40001': "transaction rollback" / "serialization failure"
            // '1213': "Deadlock found when trying to get lock; try restarting transaction"
            '40001' => $errorInfo->driverErrorCode === '1213',
            // 'HY000' CLI-specific condition
            // '1205': "Lock wait timeout exceeded; try restarting transaction"
            'HY000' => $errorInfo->driverErrorCode === '1205',
            // Any other situation.
            default => false,
        };
    }

    /**
     * @param array<int,int|string|null> $errorInfoArray
     */
    private function parseErrorInfoValueByIndex(array $errorInfoArray, int $index): ?string
    {
        return array_key_exists($index, $errorInfoArray) && $errorInfoArray[$index] !== null
            ? (string) $errorInfoArray[$index]
            : null;
    }

    /**
     * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param array<mixed>|null $originalErrorInfo
     * @return array<int,int|string|null>
     */
    private function parseOriginalErrorInfo(?array $originalErrorInfo): array
    {
        $result = [];

        if ($originalErrorInfo === null) {
            return $result;
        }

        /**
         * @phpcs:ignore SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
         * @var mixed $value
         */
        foreach ($originalErrorInfo as $key => $value) {
            $result[(int) $key] = $this->parseOriginalErrorInfoValue($value);
        }

        return $result;
    }

    private function parseOriginalErrorInfoValue(mixed $value): int|string|null
    {
        if (is_string($value) || is_int($value) || $value === null) {
            return $value;
        }

        // Should never arrive here according to documentation, but seems possible according to static analysis.
        throw new UnexpectedValueException('Unexpected value type.');
    }
}
