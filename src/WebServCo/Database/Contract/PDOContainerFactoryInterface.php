<?php

declare(strict_types=1);

namespace WebServCo\Database\Contract;

interface PDOContainerFactoryInterface
{
    public function createPDOContainer(): PDOContainerInterface;
}
