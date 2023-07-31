<?php

declare(strict_types=1);

namespace WebServCo\Database\DataTransfer;

use WebServCo\Data\Contract\Transfer\DataTransferInterface;

final class PDOConfiguration implements DataTransferInterface
{
    /**
     * @readonly
     */
    public string $driverName;
    /**
     * @readonly
     */
    public string $host;
    /**
     * @readonly
     */
    public int $port;
    /**
     * @readonly
     */
    public string $dbname;
    /**
     * @readonly
     */
    public string $username;
    /**
     * @readonly
     */
    public string $passsword;
    public function __construct(string $driverName, string $host, int $port, string $dbname, string $username, string $passsword)
    {
        $this->driverName = $driverName;
        $this->host = $host;
        $this->port = $port;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->passsword = $passsword;
    }
}
