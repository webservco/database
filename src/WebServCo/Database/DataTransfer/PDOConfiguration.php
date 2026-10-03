<?php

declare(strict_types=1);

namespace WebServCo\Database\DataTransfer;

use WebServCo\Data\Contract\Transfer\DataTransferInterface;

final readonly class PDOConfiguration implements DataTransferInterface
{
    public function __construct(
        public string $driverName,
        public string $host,
        public int $port,
        public string $dbname,
        public string $username,
        public string $passsword,
    ) {
    }
}
