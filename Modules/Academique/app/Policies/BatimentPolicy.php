<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Batiment;

class BatimentPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_BATIMENTS');
    }

    public function view(AuthorizableUser $user, Batiment $b): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_BATIMENT');
    }

    public function create(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_BATIMENT');
    }

    public function update(AuthorizableUser $user, Batiment $b): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_BATIMENT');
    }

    public function delete(AuthorizableUser $user, Batiment $b): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_BATIMENT');
    }
}
