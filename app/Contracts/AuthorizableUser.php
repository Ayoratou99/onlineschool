<?php

namespace App\Contracts;

interface AuthorizableUser
{
    public function hasRole(string $role): bool;
    public function hasPermissionTo(string $permission): bool;
}
