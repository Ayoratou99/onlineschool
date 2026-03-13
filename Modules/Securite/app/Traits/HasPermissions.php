<?php
namespace Modules\Securite\Traits;

use Modules\Securite\Models\Role;
use Modules\Securite\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissions {

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    public function hasPermissionTo(string $permission): bool
    {
        return $this->roles->map->permissions->flatten()->contains('name', $permission);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
}