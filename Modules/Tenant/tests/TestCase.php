<?php

namespace Modules\Tenant\Tests;

use Modules\Tenant\Models\Tenant;
use Tests\TestCase as RootTestCase;

abstract class TestCase extends RootTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->dropAllTenantDatabases();
    }

    protected function tearDown(): void
    {
        try {
            if (function_exists('tenancy') && tenancy()->initialized) {
                tenancy()->end();
            }
        } catch (\Throwable) {
        }

        // Capture values while the app container is still alive
        $ids = [];
        try {
            $ids = Tenant::pluck('id')->toArray();
        } catch (\Throwable) {
        }

        $prefix = config('tenancy.database.prefix', 'tenant');
        $suffix = config('tenancy.database.suffix', '');
        $pgsqlConfig = config('database.connections.pgsql');

        parent::tearDown();

        foreach ($ids as $id) {
            $name = $prefix . $id . $suffix;
            $this->dropDatabaseByName($name, $pgsqlConfig);
        }
    }

    private function dropAllTenantDatabases(): void
    {
        try {
            $pdo = $this->freshPdo();
            $prefix = config('tenancy.database.prefix', 'tenant');
            $stmt = $pdo->query("SELECT datname FROM pg_database WHERE datname LIKE '{$prefix}%'");
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $pdo->exec("SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = '{$row['datname']}' AND pid != pg_backend_pid()");
                $pdo->exec("DROP DATABASE IF EXISTS \"{$row['datname']}\"");
            }
        } catch (\Throwable) {
        }
    }

    private function dropDatabaseByName(string $name, array $pgsqlConfig): void
    {
        try {
            $pdo = new \PDO(
                sprintf('pgsql:host=%s;port=%s;dbname=%s', $pgsqlConfig['host'], $pgsqlConfig['port'], $pgsqlConfig['database']),
                $pgsqlConfig['username'],
                $pgsqlConfig['password'],
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
            $pdo->exec("SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = '{$name}' AND pid != pg_backend_pid()");
            $pdo->exec("DROP DATABASE IF EXISTS \"{$name}\"");
        } catch (\Throwable) {
        }
    }

    private function freshPdo(): \PDO
    {
        $config = config('database.connections.pgsql');
        return new \PDO(
            sprintf('pgsql:host=%s;port=%s;dbname=%s', $config['host'], $config['port'], $config['database']),
            $config['username'],
            $config['password'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }
}
