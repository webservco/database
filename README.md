# webservco/database

A PHP component/library. Please see [webservco/sandbox](https://webservco.github.io/sandbox/) for an example project using these types of components.

PDO for MySQL and MariaDB, with a connection that opens on first use, query helpers and transactions.

- Requires: `ext-pdo` with the MySQL driver, a MySQL or MariaDB server
- Related: `webservco/data` (to read typed values from result rows)

---

## Installation

```shell
composer require webservco/database
```

---

## Usage

Create the container in a factory. The `webservco/sandbox` project does it in a module's local container factory, using values from the configuration.

```php
use WebServCo\Database\Factory\PDOContainerMySQLFactory;

$pdoContainerFactory = new PDOContainerMySQLFactory(
    $configurationGetter->getString('DB_HOST'),
    $configurationGetter->getInt('DB_PORT'),
    $configurationGetter->getString('DB_NAME'),
    $configurationGetter->getString('DB_USER'),
    $configurationGetter->getString('DB_PASSWORD'),
);
// \WebServCo\Database\Contract\PDOContainerInterface
$pdoContainer = $pdoContainerFactory->createPDOContainer();
```

Inject the container (not the PDO object) into storage classes and query through it:

```php
$stmt = $pdoContainer->getPDOService()->prepareStatement(
    $pdoContainer->getPDO(),
    'SELECT id, name FROM item WHERE user_id = ? LIMIT ?',
);
$stmt->execute([$userId, 50]);

while ($row = $pdoContainer->getPDOService()->fetchAssoc($stmt)) {
    yield $this->hydrateItem($row);
}
```

### Transactions

```php
use WebServCo\Database\Service\TransactionalDatabaseService;

// \WebServCo\Database\Contract\TransactionalDatabaseServiceInterface
$transactionalDatabaseService = new TransactionalDatabaseService($pdoContainer);
$transactionalDatabaseService->beginTransaction();
// ... queries ...
$transactionalDatabaseService->commitTransaction();
```

---

## Contracts

| Interface | Implementation | Purpose |
| --- | --- | --- |
| `Contract\PDOContainerInterface` | `Container\PDOContainer` | `getPDO()` (shared, opened on first call), `createPDO()` (new connection), `getPDOService()`. |
| `Contract\PDOContainerFactoryInterface` | `Factory\PDOContainerMySQLFactory` | Creates the container from connection details. |
| `Contract\PDOServiceInterface` | `Service\PDOService` | Prepare, fetch, error info, last insert id, placeholders. |
| `Contract\TransactionalDatabaseServiceInterface` | `Service\TransactionalDatabaseService` | Begin, commit (rolls back if the commit fails) and roll back. |
| `Contract\PDOFactoryInterface` | `Factory\PDOFactory` | Creates a PDO object with the settings below. |

Namespaces are relative to `WebServCo\Database\`.

---

## Configuration

The component takes plain values and doesn't care what the keys are called. The sandbox project uses:

```ini
[Database]

DB_HOST = db
DB_PORT = 3306
DB_USER = db
DB_PASSWORD = db
DB_NAME = db
```

---

## Notes

Every connection uses `utf8mb4`, throws exceptions on errors, fetches associative arrays and uses real prepared statements (no emulation). Only the `mysql` driver is accepted.

Because the container opens the connection on first use, a long-running script doesn't hold an idle connection that the server may close ("MySQL server has gone away").

`fetchAssoc()` returns `[]` when there are no more rows. `generatePlaceholdersString($values)` returns `?,?,?` for an `IN (...)` list. `isRecoverableError()` is `true` for a deadlock or a lock wait timeout. Retry those.

---

## Development

```shell
# Start project.
ddev start
# Install dependencies.
ddev c u
# Run all validations.
ddev c c
# Run unit tests.
ddev c test:dox
```

---

## License

[MIT](LICENSE.md)
