<?php

namespace Modules\Tenant\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Securite\Database\Seeders\SecuriteDatabaseSeeder;

/**
 * Runs when a tenant DB is seeded (tenants:seed or after tenant creation).
 * Seeds the tenant with roles, permissions, and an admin user (via Securite).
 */
class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SecuriteDatabaseSeeder::class,
        ]);
    }
}
