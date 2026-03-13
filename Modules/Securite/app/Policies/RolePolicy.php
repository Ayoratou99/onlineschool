<?php

namespace Modules\Securite\Policies;

use App\Contracts\AuthorizableUser; 
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Securite\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function viewAny(AuthorizableUser $user)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_ROLES');
    }

    public function view(AuthorizableUser $user, Role $role)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_ROLE');
    }

    public function create(AuthorizableUser $user)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_ROLE');
    }

    public function update(AuthorizableUser $user, Role $role)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_ROLE');
    }
    
    public function delete(AuthorizableUser $user, Role $role)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_ROLE');
    }
}

