<?php

declare(strict_types=1);

namespace WebServCo\Database\DataTransfer;

use WebServCo\Data\Contract\Transfer\DataTransferInterface;

final class PDOConfiguration implements DataTransferInterface
{
    public function __construct(
        public readonly string $driverName,
        public readonly string $host,
        public readonly int $port,
        public readonly string $dbname,
        public readonly string $username,
        public readonly string $passsword,
    ) {
    }
}
