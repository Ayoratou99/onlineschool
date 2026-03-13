<?php

declare(strict_types=1);

namespace Modules\Tenant\Services;

use Modules\Tenant\Models\Tenant;
use Illuminate\Support\Facades\DB;

class TenantStatsService
{
    /**
     * Get stats for a single tenant (users count, database size in bytes).
     */
    public function getStatsForTenant(Tenant $tenant): array
    {
        $usersCount = 0;
        $databaseSizeBytes = 0;

        try {
            tenancy()->initialize($tenant);
            $usersCount = $this->getUsersCount();
            $databaseSizeBytes = $this->getDatabaseSizeBytes();
        } catch (\Throwable $e) {
            // Tenant DB might not exist or be inaccessible
            \Log::warning('TenantStatsService: could not get stats for tenant ' . $tenant->id . ': ' . $e->getMessage());
        } finally {
            tenancy()->end();
        }

        return [
            'users_count' => $usersCount,
            'database_size_bytes' => $databaseSizeBytes,
            'database_size_mb' => round($databaseSizeBytes / (1024 * 1024), 2),
        ];
    }

    /**
     * Get stats for all tenants (for dashboard).
     *
     * @return array<string, array{users_count: int, database_size_bytes: int, database_size_mb: float}>
     */
    public function getStatsForAllTenants(): array
    {
        $result = [];
        foreach (Tenant::all() as $tenant) {
            $result[$tenant->id] = $this->getStatsForTenant($tenant);
        }
        return $result;
    }

    protected function getUsersCount(): int
    {
        if (! class_exists(\Modules\Securite\Models\User::class)) {
            return 0;
        }
        return (int) \Modules\Securite\Models\User::count();
    }

    protected function getDatabaseSizeBytes(): int
    {
        $driver = DB::connection()->getDriverName();
        if ($driver === 'pgsql') {
            $size = DB::connection()->selectOne('SELECT pg_database_size(current_database()) AS size');
            return (int) ($size->size ?? 0);
        }
        if ($driver === 'mysql') {
            $db = DB::connection()->getDatabaseName();
            $result = DB::connection()->selectOne(
                'SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema = ?',
                [$db]
            );
            return (int) ($result->size ?? 0);
        }
        return 0;
    }
}
