<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Artisan;
use Modules\Tenant\Models\Tenant;

/**
 * Shared helpers for tenant-scoped module tests (Securite, Statistique, etc.).
 *
 * Applied via Pest's uses() in tests/Pest.php together with
 * beforeEach(initTenantForTesting) / afterEach(cleanUpTenantForTesting).
 */
trait TenantTestHelpers
{
    protected ?Tenant $tenant = null;

    protected string $tenantDomain = 'test.local';

    public function initTenantForTesting(): void
    {
        $this->tenant = Tenant::withoutEvents(fn () => Tenant::create(['id' => 'test-tenant']));
        $this->tenant->domains()->create(['domain' => $this->tenantDomain]);

        $this->ensureTenantDatabaseExists();

        Artisan::call('tenants:migrate', [
            '--tenants' => ['test-tenant'],
            '--force' => true,
        ]);

        config(['app.url' => 'http://' . $this->tenantDomain]);
    }

    public function cleanUpTenantForTesting(): void
    {
        try {
            if (function_exists('tenancy') && tenancy()->initialized) {
                tenancy()->end();
            }
        } catch (\Throwable) {
        }

        $ids = [];
        try {
            $ids = Tenant::pluck('id')->toArray();
        } catch (\Throwable) {
        }

        foreach ($ids as $id) {
            $this->dropTenantDatabaseById($id);
        }
    }

    public function tenantUrl(string $path = ''): string
    {
        $base = 'http://' . $this->tenantDomain;

        return $path ? $base . '/' . ltrim($path, '/') : $base;
    }

    public function runInTenantContext(callable $callback): mixed
    {
        tenancy()->initialize($this->tenant);

        try {
            return $callback();
        } finally {
            tenancy()->end();
        }
    }

    private function ensureTenantDatabaseExists(): void
    {
        $dbName = 'tenant' . $this->tenant->id;
        $pdo = $this->freshPdoConnection();

        $stmt = $pdo->prepare('SELECT 1 FROM pg_database WHERE datname = ?');
        $stmt->execute([$dbName]);

        if (! $stmt->fetch()) {
            $pdo->exec("CREATE DATABASE \"{$dbName}\" WITH TEMPLATE=template0");
        }
    }

    private function dropTenantDatabaseById(string $tenantId): void
    {
        $dbName = 'tenant' . $tenantId;

        try {
            $pdo = $this->freshPdoConnection();
            $pdo->exec("SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = '{$dbName}' AND pid != pg_backend_pid()");
            $pdo->exec("DROP DATABASE IF EXISTS \"{$dbName}\"");
        } catch (\Throwable) {
        }
    }

    private function freshPdoConnection(): \PDO
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
