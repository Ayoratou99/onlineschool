<?php

namespace Modules\Securite\Database\Seeders;

use Illuminate\Database\Seeder;

class SecuriteDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
