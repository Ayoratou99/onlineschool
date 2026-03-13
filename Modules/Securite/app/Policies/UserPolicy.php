<?php

namespace Modules\Securite\Policies;

use App\Contracts\AuthorizableUser; 
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Securite\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function viewAny(AuthorizableUser $user)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_UTILISATEURS');
    }

    public function view(AuthorizableUser $user, User $model)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_UTILISATEUR');
    }

    public function create(AuthorizableUser $user)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_UTILISATEUR');
    }

    public function update(AuthorizableUser $user, User $model)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_UTILISATEUR');
    }
    
    public function delete(AuthorizableUser $user, User $model)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_UTILISATEUR');
    }

    public function reset2fa(AuthorizableUser $user, User $model)
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('REINITIALISER_2FA');
    }
}

