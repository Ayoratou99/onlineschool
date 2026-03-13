<?php

declare(strict_types=1);

namespace App\Tenancy;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLDatabaseManager as BasePostgreSQLDatabaseManager;

/**
 * Runs CREATE/DROP DATABASE using a fresh PDO connection so they are never inside
 * a transaction. Required for PostgreSQL and tests using RefreshDatabase.
 * createDatabase is idempotent (skips if DB exists).
 */
class PostgreSQLDatabaseManager extends BasePostgreSQLDatabaseManager
{
    public function createDatabase(TenantWithDatabase $tenant): bool
    {
        $name = $tenant->database()->getName();
        if ($this->databaseExists($name)) {
            return true;
        }
        $this->runDdlStatement("CREATE DATABASE \"{$name}\" WITH TEMPLATE=template0");
        return true;
    }

    public function deleteDatabase(TenantWithDatabase $tenant): bool
    {
        $this->runDdlStatement("DROP DATABASE \"{$tenant->database()->getName()}\"");
        return true;
    }

    /**
     * Run a DDL statement (CREATE/DROP DATABASE) on a new PDO connection so it
     * is never inside Laravel's transaction.
     */
    private function runDdlStatement(string $sql): void
    {
        $config = config("database.connections.{$this->connection}");
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $config['host'] ?? '127.0.0.1',
            $config['port'] ?? '5432',
            $config['database'] ?? 'postgres'
        );
        $pdo = new \PDO(
            $dsn,
            $config['username'] ?? '',
            $config['password'] ?? '',
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
        $pdo->exec($sql);
    }
}
