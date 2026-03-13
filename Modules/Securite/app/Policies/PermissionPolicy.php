<?php

namespace Modules\Securite\Policies;

use App\Contracts\AuthorizableUser; 
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Securite\Models\Permission;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function viewAny(AuthorizableUser $user)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_PERMISSIONS');
    }

    public function view(AuthorizableUser $user, Permission $permission)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_PERMISSION');
    }

    public function create(AuthorizableUser $user)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_PERMISSION');
    }

    public function update(AuthorizableUser $user, Permission $permission)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_PERMISSION');
    }
    
    public function delete(AuthorizableUser $user, Permission $permission)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_PERMISSION');
    }
}

