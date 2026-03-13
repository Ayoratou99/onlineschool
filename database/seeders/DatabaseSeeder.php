<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Central: seeds central admin (Tenant module). Tenant: seeds all except Tenant module (e.g. Securite).
     */
    public function run(): void
    {
        if (function_exists('tenant') && tenant() !== null) {
            $this->call(\Modules\Securite\Database\Seeders\SecuriteDatabaseSeeder::class);
        } else {
            $this->call(\Modules\Tenant\Database\Seeders\AdminSeeder::class);
        }
    }
}
