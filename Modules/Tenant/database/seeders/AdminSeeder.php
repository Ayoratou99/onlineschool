<?php

namespace Modules\Tenant\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Tenant\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => config('tenant.admin_default_email', 'admin@central.local')],
            [
                'name' => config('tenant.admin_default_name', 'Super Admin'),
                'password' => Hash::make(config('tenant.admin_default_password', 'password')),
                'state' => 'ACTIVE',
            ]
        );
    }
}
