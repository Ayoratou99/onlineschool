<?php

namespace Modules\Securite\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Securite\Models\User;
use Modules\Securite\Models\Role;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@onlineschool.com'],
            [
                'nom' => 'Super Admin',
                'prenom' => 'Super Admin',
                'email' => 'admin@onlineschool.com',
                'password' => 'password',
                'state' => 'ACTIVE',
                'email_verified_at' => now(),
            ]
        );

        $role = Role::where('name', 'ADMIN')->firstOrFail();
        if ($role) {
            $admin->roles()->sync([$role->id]);
        }
    }
}