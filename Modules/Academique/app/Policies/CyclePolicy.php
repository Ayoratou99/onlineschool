<?php

namespace Modules\Academique\Policies;

use App\Contracts\AuthorizableUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Academique\Models\Cycle;

class CyclePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_CYCLES');
    }

    public function view(AuthorizableUser $user, Cycle $cycle): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('VOIR_CYCLE');
    }

    public function create(AuthorizableUser $user): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('CREER_CYCLE');
    }

    public function update(AuthorizableUser $user, Cycle $cycle): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('MODIFIER_CYCLE');
    }

    public function delete(AuthorizableUser $user, Cycle $cycle): bool
    {
        return $user->hasRole('ADMIN') || $user->hasPermissionTo('SUPPRIMER_CYCLE');
    }
}
