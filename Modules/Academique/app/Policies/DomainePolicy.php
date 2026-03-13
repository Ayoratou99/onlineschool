<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Domaine;

class DomainePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_DOMAINES');
    }

    public function view(AuthorizableUser $user, Domaine $domaine): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_DOMAINE');
    }

    public function create(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_DOMAINE');
    }

    public function update(AuthorizableUser $user, Domaine $domaine): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_DOMAINE');
    }

    public function delete(AuthorizableUser $user, Domaine $domaine): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_DOMAINE');
    }
}
